/*OK*/
function iniciar() {
  window.status = "Servicios RH Global";
  var x = new paws();
  x.go("operadores","listado",crealista);
}

/*OK*/
addLoad(iniciar);

/*OK*/
// Fila zombie para pre llenar la tabla del listado
zombietr = document.createElement("tr");
for (var k=0; k<3; k++) {
  zombietd = document.createElement("td");
  zombietr.appendChild(zombietd);
}

/*OK*/
// Llena la tabla del listado, es llamada mediante inicia() [PAWS: nuevos/listado]
function crealista(o) {
  var mError = false;
  var aOper = new Array();
  eval(b64.decode(o.contenido));
  if(mError) {
    alert("Ya existe un operador con ese login, favor de cambiarlo.");
  } else {
    var tlb = document.getElementById("tlb");
    while (i = tlb.firstChild) {
      tlb.removeChild(i);
    }
    if(aOper.length) {
      for (var j=0; j<aOper.length; j++) {
        var fila = zombietr.cloneNode(true);
        var primera = fila.firstChild;
          var tfolio = document.createTextNode(aOper[j][0]);
          primera.appendChild(tfolio);
        var segunda = primera.nextSibling;
          var tnombre = document.createTextNode(aOper[j][1]);
          segunda.appendChild(tnombre);
        var tercera = segunda.nextSibling;
          var tacciones = new Array("borrar","editar");
          for (var l in tacciones) {
            nliga = document.createElement("a");
            nliga.href = aOper[j][0];
            nliga.setAttribute("title",tacciones[l] + " " + aOper[j][0]);
            nliga.onclick = function() {return accion(this.title);};
            nimg = document.createElement("img");
            nimg.setAttribute("src","/interfase/" + tacciones[l] + ".gif");
            nimg.setAttribute("alt",tacciones[l]);
            nliga.appendChild(nimg);
            tercera.appendChild(nliga);
          }
        fila.className = ((j+1)%2==0) ? "even" : "odd";
        tlb.appendChild(fila);
      }
    } else {
      var filavacio = document.createElement("tr");
      filavacio.className="even";
      var celdavacio = document.createElement("td");
      celdavacio.setAttribute("colspan","3");
      celdavacio.colSpan = 3;
      var txtvacio = document.createTextNode("No hay Operadores en el Sistema");
      var br = document.createElement("br");
      celdavacio.appendChild(txtvacio);
      celdavacio.appendChild(br);
      filavacio.appendChild(celdavacio);
      tlb.appendChild(filavacio);
    }
    cierraedit();
  }
}

function cierraedit() {
  foco();
  mwHide("class:seccionedit");
}

function preparaf1(tipo,operid) {
  switch(tipo) {
    case "alta":
    document.getElementById("f1leg").firstChild.nodeValue = "Agregar Operador";
    document.getElementById("f1id").value = "alta";
    document.getElementById("f1est").selectedIndex = 1;
    document.getElementById("f1p1").checked = true;
    document.getElementById("f1p2").checked = false;
    document.getElementById("f1p3").checked = false;
    document.getElementById("f1boton").onclick = function() {
      guardaoperador();
    }
    break;
    
    case "edita":
    document.getElementById("f1leg").firstChild.nodeValue = "Editar Operador";
    document.getElementById("f1id").value = operid;
    document.getElementById("f1boton").onclick = function() {
      editaoperador();
    }
    break;
  }
  foco();
  mwShow("id:f1","");
}

function agregaoper() {
  preparaf1("alta","0");
}

function llenaoper(o) {
  preparaf1("edita","0");
  var forma = document.getElementById("editar");
  eval(b64.decode(o.contenido));
}


function accion(cad) {
  var instrucciones = cad.split(" ");
  switch(instrucciones[0]) {
    case "borrar":
       borraoperador(instrucciones[1]);
    break;
    case "editar":
      var x = new paws();
      x.addVar("oper_id",instrucciones[1]);
      x.go("operadores","datosoper",llenaoper);
    break;
    case "procesar":
       procesaoperador(instrucciones[1]);
    break;
  }
  return false;
}

function guardaoperador() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="" || forma.f1nick.value=="" || forma.f1pass.value=="" || forma.f1mail.value=="") {
    alert("Para poder guardar un Operador, deben capturarse todos los campos");
    return;
  }
    
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("login",forma.f1nick.value);
  x.addVar("pass",forma.f1pass.value);
  x.addVar("email",forma.f1mail.value);
  x.addVar("nivel",calculanivel(forma));
  x.addVar("estatus",forma.f1est.selectedIndex);
  x.go("operadores","guardaoper",crealista);
}

function editaoperador() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="" || forma.f1nick.value=="" || forma.f1pass.value=="" || forma.f1mail.value=="") {
    alert("Para poder guardar un Operador, deben capturarse todos los campos");
    return;
  }
  
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("login",forma.f1nick.value);
  x.addVar("pass",forma.f1pass.value);
  x.addVar("email",forma.f1mail.value);
  x.addVar("nivel",calculanivel(forma));
  x.addVar("estatus",forma.f1est.selectedIndex);
  x.addVar("operador_id",forma.f1id.value);
  x.go("operadores","editaoper",crealista);
}

function borraoperador(operid) {
  if(confirm("Seguro que deseas borrar a este operador?\nEsta acción no puede deshacerse después.\n\nPresiona Aceptar [OK] para borrarlo, o cancelar para continuar sin borrar al operador.")) {
    cierraedit();
    var x = new paws();
    x.addVar("operador_id",operid);
    x.go("operadores","borraoper",crealista);
  }
}

function calculanivel(f) {
  var niv = 0;
  niv+= (f.f1p1.checked) ? 1 : 0;
  niv+= (f.f1p2.checked) ? 2 : 0;
  niv+= (f.f1p3.checked) ? 4 : 0;
  return niv + "";
}
