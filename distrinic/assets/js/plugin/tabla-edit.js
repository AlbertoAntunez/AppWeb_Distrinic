var $TABLE = $('#table');
//var $BTN = $('#export-btn');
//var $EXPORT = $('#export');

$('.table-add').click(function () {
  var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide table-line').addClass('tr-visible');
  //$clone.setAttribute('id', '10');
  var id = Math.floor((Math.random() * 100) + 1);

  //$clone_ID = $TABLE.find('tr.hide');
  $clone.attr('id', 'col_'+id);

  var $td = $clone.find('td');
  //var $inp = $td.find('p');

  //$inp.attr('id', 'imp_'+id);

  var $sel = $clone.find('.col-edt-principal');
  //var $sel = $td.querySelector(".editImport");
  //$sel.attr('id','col_'+id);

  //console.log($sel.id);
  var $span = $td.find('span');
  $span.attr('id', id);

  $TABLE.find('table').append($clone);
});

$('.table-remove').click(function () {
  $(this).parents('tr').detach();
});

$('.table-up').click(function () {
  var $row = $(this).parents('tr');
  if ($row.index() === 1) return; // Don't go above the header
  $row.prev().before($row.get(0));
});

$('.table-down').click(function () {
  var $row = $(this).parents('tr');
  $row.next().after($row.get(0));
});

// A few jQuery helpers for exporting only
jQuery.fn.pop = [].pop;
jQuery.fn.shift = [].shift;
/*
$BTN.click(function () {
  var datos = tomarDatosMP();
  $EXPORT.text(datos);
});*/

function vaciarTabla(){
    var $rows = $TABLE.find('tr.tr-visible');

    $rows.each(function () {
      $(this).detach();
    });
}


function tomarDatosMP(){
  var $rows = $TABLE.find('tr:not(:hidden)');
  var headers = [];
  var data = [];
  
  // Get the headers (add special header logic here)
  $($rows.shift()).find('th:not(:empty)').each(function () {
    headers.push($(this).text().toLowerCase());
  });
  
  // Turn all existing rows into a loopable array
  $rows.each(function () {
    var $td = $(this).find('td');
    var h = {};
    
    // Use the headers from earlier to name our hash keys
    headers.forEach(function (header, i) {

      var $sele = $td.find('select');

      if($sele.eq(i).text()!=''){
        h[header] = $sele.val();
      }else{
        h[header] = $td.eq(i).text();   
      }
    });
    
    data.push(h);
  });
  
  // Output the result
  //$EXPORT.text(JSON.stringify(data));
  return JSON.stringify(data);
}