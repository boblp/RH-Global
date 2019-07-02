var aOper = new Array();
var curChange = null;
var curOnChange = null;



function iniciar() {
  window.status = "Servicios RH Global";
  var x = new paws();
  x.go("reportes","operadores",crealista);
  /* Test select
  var esteSelect = document.getElementById("tal");
  esteSelect.onchange = function() {
    this.onblur = null;
    alert(this.value);
  }
  esteSelect.onblur = function () {
    this.parentNode.removeChild(this);
  }
  */
  var changeable = _porTag("span","allowchange","eplista");
  if(changetotal = changeable.length) {
    for(i=0;i<changetotal;i++) {
      changeable[i].onclick = function() {showselect(this);};
      changeable[i].onmouseover = function() {this.className+=" arriba";};
      changeable[i].onmouseout = function() {this.className="allowchange";};
    }
  }
}

function makeselect(candid,operid,ele) {
  var sel = document.createElement("select");
  sel.className = "candid_" + candid;
  sel.onblur = function() {
    this.parentNode.replaceChild(ele,this);
  }
  sel.onchange = function() {
    this.onblur = null;
    var cndid = this.className.split("_")[1];
    cambiaoperador(this.value,cndid);
    curChange = ele;
    curOnChange = this;
  }
  
  for(j=0;j<aOper.length;j++) {
    var nOpt = document.createElement("option");
    nOpt.selected = (aOper[j][0]==operid) ? true : false;
    nOpt.value = aOper[j][0];
    var nOptext = document.createTextNode(aOper[j][1]);
    nOpt.appendChild(nOptext);
    sel.appendChild(nOpt);
  }
  return sel;
}

function showselect(ele) {
  var para = ele.id.split("_");
  if(para.length==3) {
    var theSelect = makeselect(para[1],para[2],ele);
    ele.parentNode.replaceChild(theSelect,ele);
    theSelect.focus();
  }
}

function doBack(operid,opnom) {
  var oldid = curChange.id.split("_");
  curChange.id = oldid[0] + "_" + oldid[1] + "_" + operid;
  curChange.innerHTML = opnom;
  curOnChange.parentNode.replaceChild(curChange,curOnChange);
  return;
}

function crealista(o) {
  eval(b64.decode(o.contenido));
}


addLoad(iniciar);

function cambiaoperador(operid,candid) {
  var x = new paws();
  x.addVar("candidato_id",candid);
  x.addVar("operador_id",operid);
  x.go("reportes","cambiaoper",corre);
}