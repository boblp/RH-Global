/* NTOS - utileria usada por varias funciones */
/* ************************************************************************** */
function ntos(n){
    n=n.toString(16);
    if (n.length == 1) n="0"+n;
    n="%"+n;
    return unescape(n);
}


/* BASE 64 */
/* ************************************************************************** */

function base64() {
}
base64.chars = new Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','+','/');
base64.cadena = "";
base64.cuenta = 0;
base64.setCadena = function (str){
    base64.cadena = str;
    base64.cuenta = 0;
}
base64.revchars = new Array();
for (var i=0; i < base64.chars.length; i++){
    base64.revchars[base64.chars[i]] = i;
}

base64.read = function (){    
    if (!base64.cadena) return "END_OF_INPUT";
    if (base64.cuenta >= base64.cadena.length) return "END_OF_INPUT";
    var c = base64.cadena.charCodeAt(base64.cuenta) & 0xff;
    base64.cuenta++;
    return c;
}

base64.readrev = function(){   
    if (!base64.cadena) return END_OF_INPUT;
    while (true){      
        if (base64.cuenta >= base64.cadena.length) return "END_OF_INPUT";
        var siguiente = base64.cadena.charAt(base64.cuenta);
        base64.cuenta++;
        if (base64.revchars[siguiente]){
            return base64.revchars[siguiente];
        }
        if (siguiente == 'A') return 0;
    } 
}

base64.prototype.encode = function (str){
    base64.setCadena(str);
    var result = '';
    var inBuffer = new Array(3);
    var lineCount = 0;
    var done = false;
    while (!done && (inBuffer[0] = base64.read()) != "END_OF_INPUT"){
        inBuffer[1] = base64.read();
        inBuffer[2] = base64.read();
        result += (base64.chars[ inBuffer[0] >> 2 ]);
        if (inBuffer[1] != "END_OF_INPUT"){
            result += (base64.chars [(( inBuffer[0] << 4 ) & 0x30) | (inBuffer[1] >> 4) ]);
            if (inBuffer[2] != "END_OF_INPUT"){
                result += (base64.chars [((inBuffer[1] << 2) & 0x3c) | (inBuffer[2] >> 6) ]);
                result += (base64.chars [inBuffer[2] & 0x3F]);
            } else {
                result += (base64.chars [((inBuffer[1] << 2) & 0x3c)]);
                result += ('=');
                done = true;
            }
        } else {
            result += (base64.chars [(( inBuffer[0] << 4 ) & 0x30)]);
            result += ('=');
            result += ('=');
            done = true;
        }
        lineCount += 4;
        if (lineCount >= 76){
            result += ('\n');
            lineCount = 0;
        }
    }
    return result;
}

base64.prototype.decode = function(str){
    base64.setCadena(str);
    var result = "";
    var inBuffer = new Array(4);
    var done = false;
    while (!done && (inBuffer[0] = base64.readrev()) != "END_OF_INPUT" && (inBuffer[1] = base64.readrev()) != "END_OF_INPUT"){
        inBuffer[2] = base64.readrev();
        inBuffer[3] = base64.readrev();
        result += ntos((((inBuffer[0] << 2) & 0xff)| inBuffer[1] >> 4));
        if (inBuffer[2] != "END_OF_INPUT"){
            result +=  ntos((((inBuffer[1] << 4) & 0xff)| inBuffer[2] >> 2));
            if (inBuffer[3] != "END_OF_INPUT"){
                result +=  ntos((((inBuffer[2] << 6)  & 0xff) | inBuffer[3]));
            } else {
                done = true;
            }
        } else {
            done = true;
        }
    }
    return result;
}

b64 = new base64;


/* HEXADECIMAL */
/* ************************************************************************** */

function hexcode () {
}

hexcode.digitos = new Array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');

hexcode.toHex = function(n){
    var result = ''
    var start = true;
    for (var i=32; i>0;){
        i-=4;
        var digit = (n>>i) & 0xf;
        if (!start || digit != 0){
            start = false;
            result += hexcode.digitos[digit];
        }
    }
    return (result==''?'0':result);
}

hexcode.pad = function(str, len, pad){
    var result = str;
    for (var i=str.length; i<len; i++){
        result = pad + result;
    }
    return result;
}

hexcode.prototype.encode = function(str){
    var result = "";
    for (var i=0; i<str.length; i++){
        result += hexcode.pad(hexcode.toHex(str.charCodeAt(i)&0xff),2,'0');
    }
    return result;
}

hexcode.prototype.decode = function(str){
    str = str.replace(new RegExp("s/[^0-9a-zA-Z]//g"));
    var result = "";
    var nextchar = "";
    for (var i=0; i<str.length; i++){
        nextchar += str.charAt(i);
        if (nextchar.length == 2){
            result += ntos(eval('0x'+nextchar));
            nextchar = "";
        }
    }
    return result;
    
}

hex = new hexcode;


/* MD5 HEXADECIMAL */
/* ************************************************************************** */

function md5core() {
}

md5core.hex_chr = hexcode.digitos.join("");
md5core.rhex = function(num) {
  str = "";
  for(j = 0; j <= 3; j++)
    str += md5core.hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
           md5core.hex_chr.charAt((num >> (j * 8)) & 0x0F);
  return str;
}

md5core.bloques = function(str){
  nblk = ((str.length + 8) >> 6) + 1;
  blks = new Array(nblk * 16);
  for(i = 0; i < nblk * 16; i++) blks[i] = 0;
  for(i = 0; i < str.length; i++)
    blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
  blks[i >> 2] |= 0x80 << ((i % 4) * 8);
  blks[nblk * 16 - 2] = str.length * 8;
  return blks;
}

md5core.suma = function(x, y){
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

md5core.rol = function(num, cnt){
  return (num << cnt) | (num >>> (32 - cnt));
}

md5core.cmn = function cmn(q, a, b, x, s, t){
  return md5core.suma(md5core.rol(md5core.suma(md5core.suma(a, q), md5core.suma(x, t)), s), b);
}
md5core.ff = function ff(a, b, c, d, x, s, t){
  return md5core.cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
md5core.gg = function gg(a, b, c, d, x, s, t){
  return md5core.cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
md5core.hh = function hh(a, b, c, d, x, s, t){
  return md5core.cmn(b ^ c ^ d, a, b, x, s, t);
}
md5core.ii = function ii(a, b, c, d, x, s, t){
  return md5core.cmn(c ^ (b | (~d)), a, b, x, s, t);
}

md5core.prototype.encode = function(str){
  x = md5core.bloques(str);
  a =  1732584193;
  b = -271733879;
  c = -1732584194;
  d =  271733878;

  for(i = 0; i < x.length; i += 16){
    olda = a;
    oldb = b;
    oldc = c;
    oldd = d;

    a = md5core.ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = md5core.ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = md5core.ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = md5core.ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = md5core.ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = md5core.ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = md5core.ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = md5core.ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = md5core.ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = md5core.ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = md5core.ff(c, d, a, b, x[i+10], 17, -42063);
    b = md5core.ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = md5core.ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = md5core.ff(d, a, b, c, x[i+13], 12, -40341101);
    c = md5core.ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = md5core.ff(b, c, d, a, x[i+15], 22,  1236535329);    

    a = md5core.gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = md5core.gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = md5core.gg(c, d, a, b, x[i+11], 14,  643717713);
    b = md5core.gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = md5core.gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = md5core.gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = md5core.gg(c, d, a, b, x[i+15], 14, -660478335);
    b = md5core.gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = md5core.gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = md5core.gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = md5core.gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = md5core.gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = md5core.gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = md5core.gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = md5core.gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = md5core.gg(b, c, d, a, x[i+12], 20, -1926607734);
    
    a = md5core.hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = md5core.hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = md5core.hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = md5core.hh(b, c, d, a, x[i+14], 23, -35309556);
    a = md5core.hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = md5core.hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = md5core.hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = md5core.hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = md5core.hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = md5core.hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = md5core.hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = md5core.hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = md5core.hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = md5core.hh(d, a, b, c, x[i+12], 11, -421815835);
    c = md5core.hh(c, d, a, b, x[i+15], 16,  530742520);
    b = md5core.hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = md5core.ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = md5core.ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = md5core.ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = md5core.ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = md5core.ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = md5core.ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = md5core.ii(c, d, a, b, x[i+10], 15, -1051523);
    b = md5core.ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = md5core.ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = md5core.ii(d, a, b, c, x[i+15], 10, -30611744);
    c = md5core.ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = md5core.ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = md5core.ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = md5core.ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = md5core.ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = md5core.ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = md5core.suma(a, olda);
    b = md5core.suma(b, oldb);
    c = md5core.suma(c, oldc);
    d = md5core.suma(d, oldd);
  }
  return md5core.rhex(a) + md5core.rhex(b) + md5core.rhex(c) + md5core.rhex(d);
}

md5 = new md5core;