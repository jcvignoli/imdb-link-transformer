// Function to move values inside a select box
// Comes (maybe) from http://forum.achievo.org/forum/viewtopic.php?p=10709
// rewritten a little bit for the plugin purpose

    //move 
    function move(formO,selectO,to) 
    {
        var index = selectO.selectedIndex;
        
        var selectLength  = selectO.length - 1;
        
        //error handling
        //nothing selected
        if (index == -1) return false;
        
        if(to == +1 && index == selectLength)
        {
            //alert("Cannot move down anymore!");
            return false;
        }
        else if(to == -1 && index == 0)
        {
            //alert("Cannot move up anymore!");
            return false;
        }
        
        swap(index,index+to,formO,selectO);
        return true;
    }
    
    //basic swap
    function swap(fIndex,sIndex,formO,selectO)
    {
        //store first
        fText  = selectO.options[fIndex].text;
        fValue = selectO.options[fIndex].value;
        
        
        //make first = second
        selectO.options[fIndex].text  = selectO.options[sIndex].text;
        selectO.options[fIndex].value = selectO.options[sIndex].value;  
        
        //make second = first
        selectO.options[sIndex].text = fText;
        selectO.options[sIndex].value = fValue;
        
        //amke new one be selected
        selectO.options[sIndex].selected = true;    
        
        //maintain field that stores order
        recalculateOrder(formO,selectO);
    }
    
    //store in text field current order
    //note field that it writes to is hardcoded
    function recalculateOrder(formO,selectO)
    {
        
        var sep = "";
        var newOrderText = "";
        for (i = 0; i <= selectO.options.length-1; i++) 
        {   
            //alert(selectO.options[i].value);
            //newOrderText += "" + sep + selectO.options[i].value;
            //sep = ",";
		newOrderText += selectO.options[i].value + " ";
        }
        formO.imdb_imdbwidgetorder.value  = newOrderText;
    }

