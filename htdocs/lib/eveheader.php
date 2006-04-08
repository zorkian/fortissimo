<?php
// Module: EVE-Header Class
// Site: EVE I:COM, http://www.eve-i.com
// Article: http://www.eve-i.com/article.php?id=13
// Author: __kreischweide, kreischweide@eve-i.com
//
// Edit: 2003-06-19 - nonsequitur
// Fixed logic errors on new corp roles (see below)
//
// Edit: 2003-06-16 - __kreischweide
// Added: New corp roles
//
// Edit: 2003-07-11 (Lan, LanMarkx@hotmail.com)
// Removed/added: Pilot (no longer a corp role, replaced with Security Officer (corp role 512))
// Added: Added EquipmentConfig (corp role)
//	  Hanger Query rights (granted access)
//	  Account Can Take (granted access)
//	  Account Can Query (granted access)
//	  Divisions 6 and 7
//
// Edit: 2003-07-12 - __kreischweide
// Added: New corp roles #2
//
// Edit: 2003-07-13 - __kreischweide
// Added: New bitconvert function and fixed problems with bitmask greater than 31 bits.
//
// Edit: 2006-02-02 - xb95
// Added: usingIGB
// Changed: Check function to use usingIGB

// Function to convert higher than 31bit flag values into a string
function bigdecbin($dec,$doublewords=1) { 
	$erg = ""; 
	do { 
	  $rest = $dec%2147483648; 
	  if ($rest<0) $rest+=2147483648; 
	  $erg = str_pad(decbin($rest),31,"0",STR_PAD_LEFT).$erg; 
	  $dec = ($dec-$rest)/2147483648; 
	  } while (($dec>0)&&(!($dec<1))); 
  
	  return str_pad($erg,$doublewords*31,"0",STR_PAD_LEFT); 
}

// The main class
class EVEHeader
{
	var $trust = false;
	var $CharName;
    var $CharId;
	var $CorpName;
    var $AllianceName;
	var $CorpRole;
	var $RegionName;
	var $ConstName;
	var $SystemName;
	var $StationName;

	var $isDirector = false;
	var $isPersonalManager = false;
	var $isAccountant = false;
	var $isSecurityOfficer = false;
	var $isFactoryManager = false;
	var $isStationManager = false;
	var $isAuditor = false;
	
	// Equipment Config & Deploy Space right
	// for player stations and so on if I'm not mistaken
	var $EquipmentDeploy = false;
	
	// Hangar and Account rights, split by division.
	var $Div1HangarTake = false;
	var $Div1HangarQuery = false;
	var $Div1AccountTake = false;
	var $Div1AccountQuery = false;

	var $Div2HangarTake = false;
	var $Div2HangarQuery = false;
	var $Div2AccountTake = false;
	var $Div2AccountQuery = false;
	
	var $Div3HangarTake = false;
	var $Div3HangarQuery = false;
	var $Div3AccountTake = false;
	var $Div3AccountQuery = false;

	var $Div4HangarTake = false;
	var $Div4HangarQuery = false;
	var $Div4AccountTake = false;
	var $Div4AccountQuery = false;

	var $Div5HangarTake = false;
	var $Div5HangarQuery = false;
	var $Div5AccountTake = false;
	var $Div5AccountQuery = false;

	var $Div6HangarTake = false;
	var $Div6HangarQuery = false;
	var $Div6AccountTake = false;
	var $Div6AccountQuery = false;

	var $Div7HangarTake = false;
	var $Div7HangarQuery = false;
	var $Div7AccountTake = false;
	var $Div7AccountQuery = false;

    # make sure the person is using the in-game browser, if not, redirect them
    # to a certain URL
	function Check($RedirectURL)
	{
        if ($this->usingIGB) {
            return true;
        } else {
            header("Location: " . $RedirectURL);
            return false;
        }
	}

    # see if the browser is the in-game browser
    function usingIGB() {
		$pattern = "/^EVE-minibrowser\/(\d+\.\d+)$/";
		if (preg_match($pattern, $_SERVER['HTTP_USER_AGENT'], $matches)) {
			if ($matches[1] >= 2.0) {
				return true;
            }
        }
        return false;
    }

	function TrustInit($TrustSiteURL, $TrustSiteText)
	{
		if($_SERVER['HTTP_EVE_TRUSTED']=="yes")
		{
			$this->trust = true;

			$this->CharName = $_SERVER['HTTP_EVE_CHARNAME'];
			$this->CharId = $_SERVER['HTTP_EVE_CHARID'];
			$this->CorpName = $_SERVER['HTTP_EVE_CORPNAME'];
            $this->AllianceName = $_SERVER['HTTP_EVE_ALLIANCENAME'];
			$this->RegionName = $_SERVER['HTTP_EVE_REGIONNAME'];
			$this->ConstName = $_SERVER['HTTP_EVE_CONSTELLATIONNAME'];
			$this->SystemName = $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'];
			$this->StationName = $_SERVER['HTTP_EVE_STATIONNAME'];

			// convert to bit string and turn around for easy array access
			$temp = $_SERVER['HTTP_EVE_CORPROLE']+0;
			$this->CorpRole = strrev(bigdecbin($temp,2));
			
			// read the bit values for each role
			// main roles
			if($this->CorpRole[1]=="1")	$this->isDirector = true;
			if($this->CorpRole[7]=="1")	$this->isPersonalManager = true;
			if($this->CorpRole[8]=="1")	$this->isAccountant = true;
			if($this->CorpRole[9]=="1")	$this->isSecurityOfficer = true;
			if($this->CorpRole[10]=="1")	$this->isFactoryManager = true;
			if($this->CorpRole[11]=="1")	$this->isStationManager = true;
			if($this->CorpRole[12]=="1")	$this->isAuditor = true;

			// division roles
			if($this->CorpRole[13]=="1")	$this->Div1HangarTake = true;
			if($this->CorpRole[14]=="1")	$this->Div2HangarTake = true;
			if($this->CorpRole[15]=="1")	$this->Div3HangarTake = true;
			if($this->CorpRole[16]=="1")	$this->Div4HangarTake = true;
			if($this->CorpRole[17]=="1")	$this->Div5HangarTake = true;
			if($this->CorpRole[18]=="1")	$this->Div6HangarTake = true;
			if($this->CorpRole[19]=="1")	$this->Div7HangarTake = true;

			if($this->CorpRole[20]=="1")	$this->Div1HangarQuery = true;
			if($this->CorpRole[21]=="1")	$this->Div2HangarQuery = true;
			if($this->CorpRole[22]=="1")	$this->Div3HangarQuery = true;
			if($this->CorpRole[23]=="1")	$this->Div4HangarQuery = true;
			if($this->CorpRole[24]=="1")	$this->Div5HangarQuery = true;
			if($this->CorpRole[25]=="1")	$this->Div6HangarQuery = true;
			if($this->CorpRole[26]=="1")	$this->Div7HangarQuery = true;

			if($this->CorpRole[27]=="1")	$this->Div1AccountTake = true;
			if($this->CorpRole[28]=="1")	$this->Div2AccountTake = true;
			if($this->CorpRole[29]=="1")	$this->Div3AccountTake = true;
			if($this->CorpRole[30]=="1")	$this->Div4AccountTake = true;
			if($this->CorpRole[31]=="1")	$this->Div5AccountTake = true;
			if($this->CorpRole[32]=="1")	$this->Div6AccountTake = true;
			if($this->CorpRole[33]=="1")	$this->Div7AccountTake = true;

			if($this->CorpRole[34]=="1")	$this->Div1AccountQuery = true;
			if($this->CorpRole[35]=="1")	$this->Div2AccountQuery = true;
			if($this->CorpRole[36]=="1")	$this->Div3AccountQuery = true;
			if($this->CorpRole[37]=="1")	$this->Div4AccountQuery = true;
			if($this->CorpRole[38]=="1")	$this->Div5AccountQuery = true;
			if($this->CorpRole[39]=="1")	$this->Div6AccountQuery = true;
			if($this->CorpRole[40]=="1")	$this->Div7AccountQuery = true;
			
			// equipment config & deploy space for owned space objects
			if($this->CorpRole[41]=="1")	$this->EquipmentDeploy = true;

			return true;
		}

		if($_SERVER['HTTP_EVE_TRUSTED']=="no")
			header("eve.trustMe:".$TrustSiteURL."::".$TrustSiteText);

		return false;
	}
}
?>
