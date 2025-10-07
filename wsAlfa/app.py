from flask import Flask, request, url_for, redirect, render_template, jsonify
#from flask_compress import Compress
from flask_cors import CORS

from datetime import datetime

from api.api import api_bp
from auth import validate_api_key

app = Flask(__name__)

app.register_blueprint(api_bp, url_prefix='/api')

# Comprimir respuestas
#COMPRESS_MIMETYPES = ['text/html', 'text/css', 'text/xml', 'application/json', 'application/javascript']
#COMPRESS_LEVEL = 6
#COMPRESS_MIN_SIZE = 500
#app.config['COMPRESS_ALGORITHM'] = 'gzip'

#Compress(app)
CORS(app)

@app.route('/api/test')
def test():
    return 'Servidor funcionando!'


@app.route('/api/configure')
def configure(): 
    return render_template('config.html')


@app.route('/api/save_configuration', methods = ['POST'])
def save_configuration():
    if request.method == 'POST':
        gserver = request.form['gservidor']
        gdbname = request.form['gdbname']
        gusuario = request.form['gusuario']
        gpassword = request.form['gpassword']

        file = open("db.py", "w")
        file. write("server = '" + gserver + "'\n")
        file. write("database = '" + gdbname + "'\n")
        file. write("user = '" + gusuario + "'\n")
        file. write("password = '" + gpassword + "'\n")
        file. close()

        return redirect(url_for('test'))


@app.route('/api/serie/<string:codigo>')
@validate_api_key
def serie(codigo):
    """
    Devuelve un numero de serie valido para claves de registro alfa
    """
    nro_serie = ""
    for num in codigo[::-1]:
        nro_serie += str(int(ord(num)) + codigo.__len__()).zfill(3)

    return jsonify({'serie': "0-" + nro_serie + "-0"})
    

# Filtros Jinja personalizados
@app.template_filter()
def f_str_to_date(value):
    return datetime.strptime(value, '%d/%m/%Y').strftime('%Y-%m-%d')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')

