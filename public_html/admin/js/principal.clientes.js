/*OK*/
function iniciar() {
  window.status = "Servicios RH Global";
  var x = new paws();
  x.go("clientes","listado",crealista);
}

/*OK*/
addLoad(iniciar);

/*OK*/
// Fila zombie para pre llenar la tabla del listado
zombietr = document.createElement("tr");
for (var k=0; k<4; k++) {
  zombietd = document.createElement("td");
  zombietr.appendChild(zombietd);
}

/*OK*/
// Llena la tabla del listado, es llamada mediante inicia() [PAWS: nuevos/listado]
function crealista(o) {
  var aCte = new Array();
  eval(b64.decode(o.contenido));
  var tlb = document.getElementById("tlb");
  while (i = tlb.firstChild) {
    tlb.removeChild(i);
  }
  if(aCte.length) {
    for (var j=0; j<aCte.length; j++) {
      var fila = zombietr.cloneNode(true);
      var primera = fila.firstChild;
        var tfolio = document.createTextNode(aCte[j][0]);
        primera.appendChild(tfolio);
      var segunda = primera.nextSibling;
        var tnombre = document.createTextNode(aCte[j][1]);
        segunda.appendChild(tnombre);
      var tercera = segunda.nextSibling;
        var tusuarios = document.createTextNode(aCte[j][2]);
        var ligausr = document.createElement("a");
        ligausr.className = "ligausr";
        ligausr.id = "ligausr_" + aCte[j][0];
        ligausr.href = "usuarios";
        ligausr.setAttribute("title",aCte[j][0]);
        ligausr.onmouseover = "window.status='Servicios RH Global';";
        ligausr.onclick = function() {agregausr(this.title);return false;};
        ligausrspan = document.createElement("span");
        ligausrspan.appendChild(tusuarios);
        ligausr.appendChild(ligausrspan);
        tercera.appendChild(ligausr);
      var cuarta = tercera.nextSibling;
        var tacciones = new Array("borrar","editar");
        for (var l in tacciones) {
          nliga = document.createElement("a");
          nliga.href = aCte[j][0];
          nliga.setAttribute("title",tacciones[l] + " " + aCte[j][0]);
          nliga.onclick = function() {return accion(this.title);};
          nimg = document.createElement("img");
          nimg.setAttribute("src","/interfase/" + tacciones[l] + ".gif");
          nimg.setAttribute("alt",tacciones[l]);
          nliga.appendChild(nimg);
          cuarta.appendChild(nliga);
        }
      fila.className = ((j+1)%2==0) ? "even" : "odd";
      tlb.appendChild(fila);
    }
  } else {
    var filavacio = document.createElement("tr");
    filavacio.className="even";
    var celdavacio = document.createElement("td");
    celdavacio.setAttribute("colspan","4");
    celdavacio.colSpan = 4;
    var txtvacio = document.createTextNode("No hay Clientes en el Sistema");
    var br = document.createElement("br");
    celdavacio.appendChild(txtvacio);
    celdavacio.appendChild(br);
    filavacio.appendChild(celdavacio);
    tlb.appendChild(filavacio);
  }
}

function agregausr(num) {
  var x = new paws();
  x.addVar("cte_id",num);
  x.go("clientes","listausr",llenausr);
}

function borrausr(num) {
  var x = new paws();
  x.addVar("usuario_id",num);
  x.go("clientes","borrausr",llenausr);
}


// Llena la lista de usuarios (Recibe un Paws)
function llenausr(o) {
  var aCte = new Array();
  eval(b64.decode(o.contenido));
  if(aCte.length) {
    foco();
    var leyenda = document.getElementById("f2nombrecte");
    var forma = document.getElementById("f2");
    leyenda.firstChild.nodeValue = "Cliente: " + aCte[0];
    document.getElementById("f2cid").value=aCte[1];
    document.getElementById("f2leg").innerHTML="Agregar Usuario";
    document.getElementById("f2usrid").value="0";
    document.getElementById("f2est").selectedIndex=1;
    document.getElementById("f2boton").onclick = function() { guardausuario(); }; 
    
    mwShow("id:f2","id:f1");
    if(antol = document.getElementById("olusr")) {
      forma.removeChild(antol);
    }
    if (aCte[2].length) {
      var lista = document.createElement("ul");
      lista.id = "olusr";
      for (var i=0; i<aCte[2].length; i++) {
        lusr = document.createElement("li");
        lusrbi = document.createElement("img");
        lusrbi.src = "/interfase/borrar.gif";
        lusrbi.setAttribute("alt","borrausr " + aCte[2][i][0]);
        lusrbi.onclick = function() { accion(this.alt); }
        lusr.appendChild(lusrbi);
        lusr.appendChild(document.createTextNode(" "));
        lusrei = document.createElement("img");
        lusrei.src = "/interfase/editar.gif";
        lusrei.setAttribute("alt","editausr " + aCte[2][i][0]);
        lusrei.onclick = function() { accion(this.alt); }
        lusr.appendChild(lusrei);
        lusr.appendChild(document.createTextNode(" "));
        lusrtxt = document.createTextNode(aCte[2][i][1]);
        lusr.appendChild(lusrtxt);
        lista.appendChild(lusr);
      }
      forma.appendChild(lista);
    }
    iniciar();
  }
}


// Procesa el formulario de usuario y dispara el Paws
function guardausuario() {
  var f = document.getElementById("editar");
  if(f.f2nom.value=="" || f.f2nick.value=="" || f.f2pass.value=="" || f.f2mail.value=="") {
    alert("Deben llenarse todos los campos para proceder");
  } else {
    var x = new paws();
    x.addVar("nombre",f.f2nom.value);
    x.addVar("nick",f.f2nick.value);
    x.addVar("pass",f.f2pass.value);
    x.addVar("email",f.f2mail.value);
    x.addVar("estatus",f.f2est.selectedIndex);
    x.addVar("cliente_id",f.f2cid.value);
    x.go("clientes","guardausr",llenausr);
  }
}

// Procesa el formulario de usuario y dispara el Paws
function cambiausuario() {
  var f = document.getElementById("editar");
  if(f.f2nom.value=="" || f.f2nick.value=="" || f.f2pass.value=="" || f.f2mail.value=="") {
    alert("Deben llenarse todos los campos para proceder");
  } else {
    var x = new paws();
    x.addVar("nombre",f.f2nom.value);
    x.addVar("nick",f.f2nick.value);
    x.addVar("pass",f.f2pass.value);
    x.addVar("email",f.f2mail.value);
    x.addVar("estatus",f.f2est.selectedIndex);
    x.addVar("cliente_id",f.f2cid.value);
    x.addVar("usuario_id",f.f2usrid.value);
    x.go("clientes","editausr",llenausr);
  }
}

function cierraedit() {
  foco();
  mwHide("class:seccionedit");
}

function preparaf1(tipo,cteid) {
  switch(tipo) {
    case "alta":
    document.getElementById("f1leg").firstChild.nodeValue = "Agregar Cliente";
    document.getElementById("f1id").value = "alta";
    document.getElementById("f1est").selectedIndex = 1;
    document.getElementById("f1boton").onclick = function() {
      guardacliente();
    }
    break;
    
    case "edita":
    document.getElementById("f1leg").firstChild.nodeValue = "Editar Cliente";
    document.getElementById("f1id").value = cteid;
    document.getElementById("f1boton").onclick = function() {
      editacliente();
    }
    break;
  }
  foco();
  mwShow("id:f1","id:f2");
}

function agregacte() {
  preparaf1("alta","0");
}

function llenacte(o) {
  preparaf1("edita","0");
  var forma = document.getElementById("editar");
  eval(b64.decode(o.contenido));
}

function llenadatosusr(o) {
  document.getElementById("f2leg").innerHTML="Editar Usuario";
  document.getElementById("f2boton").onclick = function() { cambiausuario(); };
  var forma = document.getElementById("editar");
  eval(b64.decode(o.contenido));
}

function accion(cad) {
  var instrucciones = cad.split(" ");
  switch(instrucciones[0]) {
    case "borrar":
       borracliente(instrucciones[1]);
    break;
    case "editar":
      var x = new paws();
      x.addVar("cte_id",instrucciones[1]);
      x.go("clientes","datoscte",llenacte);
    break;
    case "procesar":
       procesacliente(instrucciones[1]);
    break;
    
    case "borrausr":
      if(confirm("Seguro que deseas eliminar este usuario?\nEsta acción no puede deshacerse después.\n\nPresiona Aceptar [OK] para borrarlo, o cancelar para continuar sin borrar el usuario.")) {
        borrausr(instrucciones[1]);
      }
    break;
    
    case "editausr":
      var x = new paws();
      x.addVar("usr_id",instrucciones[1]);
      x.go("clientes","datosusr",llenadatosusr);
    break;
  }
  return false;
}

function guardacliente() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="") {
    alert("Para poder guardar un Cliente, debe capturarse su nombre");
    return;
  }
    
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("estatus",forma.f1est.selectedIndex);
  x.go("clientes","guardacte",crealista);
  cierraedit();
}

function editacliente() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="") {
    alert("Para poder guardar un Cliente, debe capturarse su nombre");
    return;
  }
  
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("estatus",forma.f1est.selectedIndex);
  x.addVar("cliente_id",forma.f1id.value);
  x.go("clientes","editacte",crealista);
  cierraedit();
}

function borracliente(cteid) {
  if(confirm("Seguro que deseas borrar a este cliente y todos sus usuarios guardados?\nEsta acción no puede deshacerse después.\n\nPresiona Aceptar [OK] para borrarlo, o cancelar para continuar sin borrar al cliente.")) {
    cierraedit();
    var x = new paws();
    x.addVar("cliente_id",cteid);
    x.go("clientes","borracte",crealista);
  }
}
