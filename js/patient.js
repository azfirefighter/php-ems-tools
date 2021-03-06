// <?php
/*
 +--------------------------------------------------------------------------------------------------------+
 | PHP EMS Tools      http://www.php-ems-tools.com                                                        |
 +--------------------------------------------------------------------------------------------------------+
 | Time-stamp: "2010-08-24 22:40:25 jantman"                                                              |
 +--------------------------------------------------------------------------------------------------------+
 | Copyright (c) 2009, 2010 Jason Antman. All rights reserved.                                            |
 |                                                                                                        |
 | This program is free software; you can redistribute it and/or modify                                   |
 | it under the terms of the GNU General Public License as published by                                   |
 | the Free Software Foundation; either version 3 of the License, or                                      |
 | (at your option) any later version.                                                                    |
 |                                                                                                        |
 | This program is distributed in the hope that it will be useful,                                        |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of                                         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                                          |
 | GNU General Public License for more details.                                                           |
 |                                                                                                        |
 | You should have received a copy of the GNU General Public License                                      |
 | along with this program; if not, write to:                                                             |
 |                                                                                                        |
 | Free Software Foundation, Inc.                                                                         |
 | 59 Temple Place - Suite 330                                                                            |
 | Boston, MA 02111-1307, USA.                                                                            |
 +--------------------------------------------------------------------------------------------------------+
 |Please use the above URL for bug reports and feature/support requests.                                  |
 +--------------------------------------------------------------------------------------------------------+
 | Authors: Jason Antman <jason@jasonantman.com>                                                          |
 +--------------------------------------------------------------------------------------------------------+
 | $LastChangedRevision:: 67                                                                            $ |
 | $HeadURL:: http://svn.jasonantman.com/newcall/js/patient.js                                          $ |
 +--------------------------------------------------------------------------------------------------------+
*/

/**
 * Functions related to patient search, add, update.
 *
 * @package MPAC-NewCall-JS
 */

function findPatient()
{
  // shows the form to find a patient

  var foo = document.getElementById("popuptitle");
  foo.innerHTML = "Search for Patient";
  
  newFindPtFormRequest('findPtForm.php');
}

function newFindPtFormRequest(url)
{
  document.getElementById('popupbody').innerHTML = '<span style="text-align: center"><ing src="bigrotation2.gif" /></span>';
  doHTTPrequest(url, handleNewFindPtFormRequest);
  // TODO: add an error var to reload the form if we have errors
}

function handleNewFindPtFormRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    document.getElementById('popupbody').innerHTML = response;
    showPopup("Find Patient");
  }
}

function submitFindPtForm()
{
  var url = "findPtForm.php?";
  if(document.getElementById("FindPt_FirstName").value != "")
    {
      url = url + "FirstName=" + escape(document.getElementById("FindPt_FirstName").value);
    }
  if(document.getElementById("FindPt_LastName").value != "")
    {
      if(url.substring(-1) != "?" && url.substring(-1) != "&"){ url = url + "&"; }
      url = url + "LastName=" + escape(document.getElementById("FindPt_LastName").value);
    }
  if(document.getElementById("FindPt_DOB").value != "")
    {
      if(url.substring(-1) != "?" && url.substring(-1) != "&"){ url = url + "&"; }
      url = url + "DOB=" + escape(document.getElementById("FindPt_DOB").value);
    }
  if(document.getElementById("FindPt_Address").value != "")
    {
      if(url.substring(-1) != "?" && url.substring(-1) != "&"){ url = url + "&"; }
      url = url + "Address=" + escape(document.getElementById("FindPt_Address").value);
    }
  newFindPtFormRequest(url);
}


// 
// UPDATE PATIENT
//

function updatePatient(id)
{
  var foo = document.getElementById("popuptitle");
  foo.innerHTML = "Update Patient Information";
  
  newUpdatePtFormRequest('updatePt.php?id=' + id);
}

function updatePatientByPkey(pkey)
{
  var foo = document.getElementById("popuptitle");
  foo.innerHTML = "Update Patient Information";
  
  newUpdatePtFormRequest('updatePt.php?pkey=' + pkey);
}

function addPatient()
{
  var foo = document.getElementById("popuptitle");
  foo.innerHTML = "Add New Patient";
  newUpdatePtFormRequest('updatePt.php?id=-1');
}

function newUpdatePtFormRequest(url)
{
  document.getElementById('popupbody').innerHTML = '<span style="text-align: center"><ing src="bigrotation2.gif" /></span>';
  doHTTPrequest(url, handleUpdatePtFormRequest);
  // TODO: add an error var to reload the form if we have errors
}

function handleUpdatePtFormRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    document.getElementById('popupbody').innerHTML = response;
    showPopup("Add &#47; Update Patient");
  }
}

function validatePatientForm()
{
  return 1; // if OK
}

function submitPatientForm()
{
  // validate the input
  if(validatePatientForm() != 1)
  {
      return;
  }
  
  var s = "updatePtHandler.php?";
  for(i=0; i<document.updatePtForm.elements.length; i++)
  {   
    if(document.updatePtForm.elements[i].type=="radio" && document.updatePtForm.elements[i].checked == false)
    {

    }
    else if(document.updatePtForm.elements[i].type=="radio" && document.updatePtForm.elements[i].checked == true)
    {
      if(s.charAt(s.length-1)!="?"){ s = s + "&";}
      s = s + escape(document.updatePtForm.elements[i].name);
      s = s + "=";
      s = s + escape(document.updatePtForm.elements[i].value);
    }
    else
    {
      if(s.charAt(s.length-1)!="?"){ s = s + "&";}
      s = s + escape(document.updatePtForm.elements[i].name);
      s = s + "=";
      s = s + escape(document.updatePtForm.elements[i].value);
    }
  }
  newSubmitUpdatePtFormRequest(s);
  //hidePopup();
}

function newSubmitUpdatePtFormRequest(url)
{
  doHTTPrequest(url, handleSubmitUpdatePtFormRequest);
}

function handleSubmitUpdatePtFormRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    if(response.substr(0, 5) == "ERROR")
    {
      alert(response);
      return;
    }
    
    // else update the patient
    setPatientByPkey(response);
    hidePopup();
  }
}

function setPatient(id)
{
  doHTTPrequest(("getPt.php?id=" + id), handleNewSetPtRequest);
  alert("Please ensure that patient information is correct.");
}

function setPatientByPkey(pkey)
{
  doHTTPrequest(("getPt.php?pkey=" + pkey), handleNewSetPtRequest);
}

function handleNewSetPtRequest()
{
  if(http.readyState == 4)
  {
    var response = http.responseText;
    var JSONobject = JSON.parse(response);
    if(JSONobject && JSONobject.ERROR)
      {
	alert("ERROR: " + JSONobject.ERROR);
	return;
      }
    // if we get here, we have the object and no error
    //JSON: id, DOB, Age, FirstName, LastName, MiddleName, Address, Town, Sex
    if(JSONobject && JSONobject.id)
      {
	document.getElementById("ptpkey").value = JSONobject.pkey;
      }
    else
      {
	document.getElementById("ptpkey").value = "-1";
      }
    
    if(JSONobject && JSONobject.DOB)
      {
	document.getElementById("DOB").value = JSONobject.DOB;
      }
    else
      {
	document.getElementById("DOB").value = "";
      }
    
    if(JSONobject && JSONobject.Age)
      {
	document.getElementById("age").value = JSONobject.Age;
      }
    else
      {
	document.getElementById("age").value = "";
      }

    if(JSONobject && JSONobject.FirstName)
      {
	document.getElementById("NameFirst").value = JSONobject.FirstName;
      }
    else
      {
	document.getElementById("NameFirst").value = "";
      }
    
    if(JSONobject && JSONobject.LastName)
      {
	document.getElementById("NameLast").value = JSONobject.LastName;
      }
    else
      {
	document.getElementById("NameLast").value = "";
      }
    
    if(JSONobject && JSONobject.MiddleName)
      {
	document.getElementById("NameMiddle").value = JSONobject.MiddleName;
      }
    else
      {
	document.getElementById("NameMiddle").value = "";
      }
    
    if(JSONobject && JSONobject.DisplayAddress)
      {
	document.getElementById("Address").value = JSONobject.DisplayAddress;
      }
    else
      {
	document.getElementById("Address").value = "";
      }
    
    /*
    if(JSONobject && JSONobject.Town)
      {
	// TODO - select
	document.getElementById("AddressCity").value = JSONobject.Town;
      }
    */
    
    if(JSONobject && JSONobject.Sex)
      {
	// male or female
	if(JSONobject.Sex == "Female")
	  {
	    document.getElementById("sex").value = "Female";
	  }
	else if(JSONobject.Sex == "Male")
	{
	  document.getElementById("sex").value = "Male";
	}
      }
    
    hidePopup();
  }
}
