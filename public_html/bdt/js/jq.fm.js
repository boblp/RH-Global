/**
 * This function sets the values of form element variables from an array
 * This is the reverse process of Mark Constable's serialize function
 * It is expected to be used as a call back for an ajax call that retrieves the form data
 * @param data : array or hash containing name,value pairs for elements in the form
 * 
 * Examples
 *
 * 1. Deserialize from an array
 *    $('#form-id').deserialize([{'name':'firstname','value':'John'},{'name':'lastname','value':'Resig'}]);
 *
 * 2. Deserialize from a hash(object)
 *    $('#form-id').deserialize({'firstname':'John','lastname':'Resig'});
 *
 * 3. Deserialize multiple options for select/radio/checkbox
 *    $('#form-id').deserialize({'toppings':['capsicum','mushroom','extra_cheese'],'size':'medium'})
 * which will set the corresponding select/radio/checkbox options for toppings
 *
 * @return         the jQuery Object
 * @author         Ashutosh Bijoor (bijoor@reach1to1.com)
 * @version        0.32
 */
$.fn.deserialize = function(d) {
	var data=d;
	var self=this;
	if (typeof d == 'undefined') {
		return self;
	}
	// check if data is an array, and convert to hash, converting multiple entries of same name to an array
	if (d.constructor == Array) {
		data={};
		for(var i=0;i<d.length;i++) {
			if (typeof data[d[i].name] != 'undefined') {
				if (data[d[i].name].constructor!= Array) {
					data[d[i].name]=[data[d[i].name],d[i].value];
				} else {
					data[d[i].name].push(d[i].value);
				}
			} else {
				data[d[i].name]=d[i].value;
			}
		}
	}
	
	// now data is a hash. insert each parameter into the form
	$('input,select,textarea',self).each(function() {
		var p=this.name;
		var v = ((p && typeof data[p] != 'undefined')?((data[p].constructor==Array)?data[p]:[data[p]]):[]);
		switch(this.type || this.tagName.toLowerCase()) {
			case "radio":
			case "checkbox":
				this.checked=false;
				for(var i=0;i<v.length;i++) {
					this.checked|=(this.value!='' && v[i]==this.value);
				}
				break;
                        case "select-multiple" || "select":
				for(var i=0;i<this.options.length;i++) {
					this.options[i].selected=false;
					for(var j=0;j<v.length;j++) {
						this.options[i].selected|=(this.options[i].value!='' && this.options[i].value==v[j]);
					}
				}
				break;
                        case "button":
                        case "submit":
                                this.value=v.length>0?v.join(','):this.value;
                                break;
			default:
				this.value=v.join(',');
		}
	});	
	return self;
};