<?php

class Channel
{



	function hasAttribute($attributeName){
		return property_exists($this, "_$attributeName");
	}
}

?>