function iniciar() {
  window.status = "Servicios RH Global";
  var x = new paws();
  x.go("nuevos","listado",crealista);
}

addLoad(iniciar);

// Fila zombie para pre llenar la tabla del listado
zombietr = document.createElement("tr");
for (var k=0; k<4; k++) {
  zombietd = document.createElement("td");
  zombietr.appendChild(zombietd);
}


// Llena la tabla del listado, es llamada mediante inicia() [PAWS: nuevos/listado]
function crealista(o) {
  var aCand = new Array();
  eval(b64.decode(o.contenido));
  var tlb = document.getElementById("tlb");
  while (i = tlb.firstChild) {
    tlb.removeChild(i);
  }
  if(aCand.length) {
    for (var j=0; j<aCand.length; j++) {
      var fila = zombietr.cloneNode(true);
      var primera = fila.firstChild;
        var tfolio = document.createTextNode(aCand[j][0]);
        if(aCand[j][3] & 6) {
          imgAlert = document.createElement("img");
          imgAlert.setAttribute("src","/interfase/alert.gif");
          imgAlert.setAttribute("alt",aCand[j][4]);
          imgAlert.onclick = function() { alert(this.alt); return false; }
          primera.appendChild(imgAlert);
        }
        primera.appendChild(tfolio);
      var segunda = primera.nextSibling;
        var tnombre = document.createTextNode(aCand[j][1]);
        segunda.appendChild(tnombre);
      var tercera = segunda.nextSibling;
        var templeos = document.createTextNode(aCand[j][2]);
        var ligaemp = document.createElement("a");
        ligaemp.className = "ligaemp";
        ligaemp.id = "ligaemp_" + aCand[j][0];
        ligaemp.href = "empleos";
        ligaemp.setAttribute("title",aCand[j][0]);
        ligaemp.onmouseover = "window.status='Servicios RH Global';";
        ligaemp.onclick = function() {agregaemp(this.title);return false;};
        ligaempspan = document.createElement("span");
        ligaempspan.appendChild(templeos);
        ligaemp.appendChild(ligaempspan);
        tercera.appendChild(ligaemp);
      var cuarta = tercera.nextSibling;
        var tacciones = new Array("borrar","editar","procesar");
        for (var l in tacciones) {
          nliga = document.createElement("a");
          nliga.href = aCand[j][0];
          nliga.setAttribute("title",tacciones[l] + " " + aCand[j][0]);
          nliga.onclick = function() {return accion(this.title);};
          nliga.style.marginRight = "7px";
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
    var txtvacio = document.createTextNode("No hay candidatos nuevos");
    var br = document.createElement("br");
    celdavacio.appendChild(txtvacio);
    celdavacio.appendChild(br);
    filavacio.appendChild(celdavacio);
    tlb.appendChild(filavacio);
  }
}

function agregaemp(num) {
  var x = new paws();
  x.addVar("cand_id",num);
  x.go("nuevos","listaemp",llenaemp);
}

function borraemp(num) {
  var x = new paws();
  x.addVar("empleo_id",num);
  x.go("nuevos","borraemp",llenaemp);
}


// Llena la lista de empleos (Recibe un Paws)
function llenaemp(o) {
  foco();
  var aCand = new Array();
  eval(b64.decode(o.contenido));
  if(aCand.length) {
    var leyenda = document.getElementById("f2nombrecan");
    var forma = document.getElementById("f2");
    leyenda.firstChild.nodeValue = "Para: " + aCand[0];
    document.getElementById("f2cid").value=aCand[1];
    document.getElementById("f2leg").innerHTML="Agregar Empleo";
    document.getElementById("f2empid").value="0";
    document.getElementById("f2boton").onclick = function() { guardaempleo(); }; 
    
    mwShow("id:f2","id:f1");
    if(antol = document.getElementById("olemp")) {
      forma.removeChild(antol);
    }
    if (aCand[2].length) {
      var lista = document.createElement("ul");
      lista.id = "olemp";
      for (var i=0; i<aCand[2].length; i++) {
        lemp = document.createElement("li");
        lempbi = document.createElement("img");
        lempbi.src = "/interfase/borrar.gif";
        lempbi.setAttribute("alt","borraemp " + aCand[2][i][0]);
        lempbi.onclick = function() { accion(this.alt); }
        lemp.appendChild(lempbi);
        lemp.appendChild(document.createTextNode(" "));
        lempei = document.createElement("img");
        lempei.src = "/interfase/editar.gif";
        lempei.setAttribute("alt","editaemp " + aCand[2][i][0]);
        lempei.onclick = function() { accion(this.alt); }
        lemp.appendChild(lempei);
        lemp.appendChild(document.createTextNode(" "));
        lemptxt = document.createTextNode(aCand[2][i][1]);
        lemp.appendChild(lemptxt);
        lista.appendChild(lemp);
      }
      forma.appendChild(lista);
    }
  }
  iniciar();
}


// Procesa el formulario de empleo y dispara el Paws
function guardaempleo() {
  var f = document.getElementById("editar");
  var f_candidato_id = f.f2cid.value;
  var f_empresa = f.f2emp.value;
  var f_telefonos = f.f2tel.value;
  var f_lada = f.f2lada.value;
  var f_jefe = f.f2jef.value;
  var f_puesto = f.f2pue.value;
  var f_fechaing = f.f2fa.value;
  var f_fechabaja = f.f2fb.value;
  var f_motivobaja = f.f2mot.value;
  if (f_empresa == "") {
    alert("El nombre de la empresa no puede estar vacío");
    return;
  }
  if (f_lada == "" || f_telefonos == "" || f_jefe == "" || f_puesto == "" || f_fechaing == "" || f_fechabaja == "" || f_motivobaja == "") {
    if (!confirm("Seguro que deseas grabar este empleo sin todos sus campos llenos?\nLa ausencia de algunos de estos campos pueden causar \nretraso en el proceso, o el rechazo de la solicitud entera.\n\nPRESIONA ACEPTAR [OK] PARA GRABARLO ASI\nCANCELAR PARA CONTINUAR EDITANDOLO.")) {
      return;
    }
  }
  var x = new paws();
  x.addVar("candidato_id",f_candidato_id);
  x.addVar("empresa",f_empresa);
  x.addVar("telefonos",f_telefonos);
  x.addVar("lada",f_lada);
  x.addVar("jefe",f_jefe);
  x.addVar("puesto",f_puesto);
  x.addVar("fechaing",f_fechaing);
  x.addVar("fechabaja",f_fechabaja);
  x.addVar("motivobaja",f_motivobaja);
  x.go("nuevos","guardaemp",llenaemp);
}

// Procesa el formulario de empleo y dispara el Paws
function cambiaempleo() {
  var f = document.getElementById("editar");
  var f_candidato_id = f.f2cid.value;
  var f_empleo_id = f.f2empid.value;
  var f_empresa = f.f2emp.value;
  var f_telefonos = f.f2tel.value;
  var f_lada = f.f2lada.value;
  var f_jefe = f.f2jef.value;
  var f_puesto = f.f2pue.value;
  var f_fechaing = f.f2fa.value;
  var f_fechabaja = f.f2fb.value;
  var f_motivobaja = f.f2mot.value;
  if (f_empresa == "") {
    alert("No se puede agregar un empleo sin nombre de la empresa o telefono");
    return;
  }
  if (f_jefe == "" || f_puesto == "" || f_fechaing == "" || f_fechabaja == "" || f_motivobaja == "") {
    if (!confirm("Seguro que deseas grabar este empleo sin todos sus campos llenos?\nLa ausencia de algunos de estos campos pueden causar \nretraso en el proceso, o el rechazo de la solicitud entera.\n\nPRESIONA ACEPTAR [OK] PARA GRABARLO ASI\nCANCELAR PARA CONTINUAR EDITANDOLO.")) {
      return;
    }
  }
  var x = new paws();
  x.addVar("candidato_id",f_candidato_id);
  x.addVar("empleo_id",f_empleo_id);
  x.addVar("empresa",f_empresa);
  x.addVar("telefonos",f_telefonos);
  x.addVar("lada",f_lada);
  x.addVar("jefe",f_jefe);
  x.addVar("puesto",f_puesto);
  x.addVar("fechaing",f_fechaing);
  x.addVar("fechabaja",f_fechabaja);
  x.addVar("motivobaja",f_motivobaja);
  x.go("nuevos","editaemp",llenaemp);
}

function cierraedit() {
  foco();
  mwHide("class:seccionedit");
}

function preparaf1(tipo,candid) {
  switch(tipo) {
    case "alta":
    document.getElementById("f1leg").firstChild.nodeValue = "Alta de Candidato";
    document.getElementById("f1id").value = "alta";
    document.getElementById("f1boton").onclick = function() {
      guardacandidato();
    }
    break;
    
    case "edita":
    document.getElementById("f1leg").firstChild.nodeValue = "Edición de Candidato";
    document.getElementById("f1id").value = candid;
    document.getElementById("f1boton").onclick = function() {
      editacandidato();
    }
    break;
  }
  foco();
  mwShow("id:f1","id:f2");
}

function agregacand() {
  preparaf1("alta","0");
}

function llenacand(o) {
  preparaf1("edita","0");
  var forma = document.getElementById("editar");
  eval(b64.decode(o.contenido));
}

function llenadatosemp(o) {
  document.getElementById("f2leg").innerHTML="Editar Empleo";
  document.getElementById("f2boton").onclick = function() { cambiaempleo(); };
  var forma = document.getElementById("editar");
  eval(b64.decode(o.contenido));
}

function accion(cad) {
  var instrucciones = cad.split(" ");
  switch(instrucciones[0]) {
    case "borrar":
       borracandidato(instrucciones[1]);
    break;
    case "editar":
      var x = new paws();
      x.addVar("cand_id",instrucciones[1]);
      x.go("nuevos","datoscand",llenacand);
    break;
    case "procesar":
       procesacandidato(instrucciones[1]);
    break;
    
    case "borraemp":
      if(confirm("Seguro que deseas eliminar este empleo?\nEsta acción no puede deshacerse después.\n\nPresiona Aceptar [OK] para borrarlo, o cancelar para continuar sin borrar el empleo.")) {
        borraemp(instrucciones[1]);
      }
    break;
    
    case "editaemp":
      var x = new paws();
      x.addVar("emp_id",instrucciones[1]);
      x.go("nuevos","datosemp",llenadatosemp);
    break;
  }
  return false;
}

function guardacandidato() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="" || forma.f1sol.value=="" || forma.f1fna.value=="" || forma.f1fnm.value=="" || forma.f1fnd.value=="") {
    alert("Para poder guardar un candidato, debe capturarse como mínimo\nsu nombre y fecha de nacimiento. Además del nombre de quien solicita.");
    return;
  }
  
  // La fecha es correcta??
  var fechaok = checafecha(forma.f1fna.value,forma.f1fnm.value,forma.f1fnd.value);
  if(fechaok!="OK") {
    alert(fechaok);
    return;
  }
  
  // Capturaron IMSS y es correcto? (Si no lo capturan, no lo checa pero si pasa)
  var patronimss = /^([0-9]{11})$/;
  forma.f1imss.value = forma.f1imss.value.replace(/\D/g,"");
  if(forma.f1imss.value!="" && !forma.f1imss.value.match(patronimss)) {
    alert("El número de IMSS debe ser de 11 digitos");
    return;
  }
  
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("solpor",forma.f1sol.value);
  x.addVar("domicilio",forma.f1dom.value);
  x.addVar("ciudad",forma.f1ciu.value);
  x.addVar("telefono",forma.f1tel.value);
  x.addVar("telefonorec",forma.f1telr.value);
  x.addVar("lugarnac",forma.f1lnac.value);
  x.addVar("fechanac",forma.f1fna.value + "/" + forma.f1fnm.value + "/" + forma.f1fnd.value);
  x.addVar("imss",forma.f1imss.value);
  x.addVar("familiares",forma.f1fam.value); 
  x.go("nuevos","guardacand",crealista);
  cierraedit();
}

function editacandidato() {
  var forma = document.getElementById("editar");
  
  // Trae los campos imprescindibles??
  if(forma.f1nom.value=="" || forma.f1sol.value=="" || forma.f1fna.value=="" || forma.f1fnm.value=="" || forma.f1fnd.value=="") {
    alert("Para poder guardar un candidato, debe capturarse como mínimo\nsu nombre y fecha de nacimiento. Además del nombre de quien solicita.");
    return;
  }
  
  // La fecha es correcta??
  var fechaok = checafecha(forma.f1fna.value,forma.f1fnm.value,forma.f1fnd.value);
  if(fechaok!="OK") {
    alert(fechaok);
    return;
  }
  
  // Capturaron IMSS y es correcto? (Si no lo capturan, no lo checa pero si pasa)
  var patronimss = /^([0-9]{11})$/;
  forma.f1imss.value = forma.f1imss.value.replace(/\D/g,"");
  if(forma.f1imss.value!="" && !forma.f1imss.value.match(patronimss)) {
    alert("El número de IMSS debe ser de 11 digitos");
    return;
  }
  
  var x = new paws();
  x.addVar("nombre",forma.f1nom.value);
  x.addVar("solpor",forma.f1sol.value);
  x.addVar("domicilio",forma.f1dom.value);
  x.addVar("ciudad",forma.f1ciu.value);
  x.addVar("telefono",forma.f1tel.value);
  x.addVar("telefonorec",forma.f1telr.value);
  x.addVar("lugarnac",forma.f1lnac.value);
  x.addVar("fechanac",forma.f1fna.value + "/" + forma.f1fnm.value + "/" + forma.f1fnd.value);
  x.addVar("imss",forma.f1imss.value);
  x.addVar("candidato_id",forma.f1id.value);
  x.go("nuevos","editacand",crealista);
  cierraedit();
}

function borracandidato(candid) {
  if(confirm("Seguro que deseas borrar a este candidato y todos sus empleos guardados?\nEsta acción no puede deshacerse después.\n\nPresiona Aceptar [OK] para borrarlo, o cancelar para continuar sin borrar al candidato.")) {
    cierraedit();
    var x = new paws();
    x.addVar("candidato_id",candid);
    x.go("nuevos","borracand",crealista);
  }
}

function procesacandidato(candid) {
  if(confirm("Enviar candidato para su investigación?.\n\nPresiona Aceptar [OK] para enviarlo, o cancelar para continuar editando.")) {
    cierraedit();
    var x = new paws();
    x.addVar("candidato_id",candid);
    x.go("nuevos","procesacand",crealista);
  }
}

