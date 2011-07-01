<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usersclass
 *
 * @author William
 */
class Usersclass {
    public function getAdvancedUserHTML($IMthreshold='1.0', $TMLthreshold='2.0', $ScreeningThreshold='2.0', $IntensityLimit='0.03', $CMthreshold='1.0'){
        
        return '<table class="advancedusers">
                <tr class="advancedusers">
                    <td class="advancedusersleft">Initial Match Threshold:</td>
                    <td class="advancedusersright">
                        <input type="text" id="IMthreshold" name="IMthreshold" size="5" value="'.$IMthreshold.'"
                        onmouseover="Tip(\'Threshold used during the matching between precursor ions and disulfide bonded structures\')"
                        onmouseout="UnTip()"></input>
                        * (default: +-1.0)
                    </td>
                </tr>
                <!--
                <tr class="advancedusers">
                    <td class="advancedusersleft">MS/MS Formation Threshold:</td>
                    <td class="advancedusersright">
                        <input type="text" id="TMLthreshold" name="TMLthreshold" size="5" value="'.$TMLthreshold.'"
                        onmouseover="Tip(\'Threshold used when expanding m/z values from a DTA file according to its precursor ion charge state\')"
                        onmouseout="UnTip()"></input>
                        * (default: 2.0)
                    </td>
                </tr>
                <tr class="advancedusers">
                    <td class="advancedusersleft">MS/MS Screening Threshold:</td>
                    <td class="advancedusersright">
                        <input type="text" id="ScreeningThreshold" name="ScreeningThreshold" size="5" value="'.$ScreeningThreshold.'"
                        onmouseover="Tip(\'Threshold used while selecting a representative median value for \\\'close\\\' m/z values\')"
                        onmouseout="UnTip()"></input>
                        * (default: 2.0)
                    </td>
                </tr>
                -->
                <tr class="advancedusers">
                    <td class="advancedusersleft">MS/MS Intensity Limit:</td>
                    <td class="advancedusersright">
                        <input type="text" id="IntensityLimit" name="IntensityLimit" size="5" value="'.$IntensityLimit.'"
                        onmouseover="Tip(\'Lowest m/z intensity limit. (IntensityLimit x Maximum Intensity)\')"
                        onmouseout="UnTip()"></input>
                        * (default: 0.03)
                    </td>
                </tr>
                <tr class="advancedusers">
                    <td class="advancedusersleft">Confirmed Match Threshold:</td>
                    <td class="advancedusersright">
                        <input type="text" id="CMthreshold" name="CMthreshold" size="5" value="'.$CMthreshold.'"
                        onmouseover="Tip(\'Threshold used while matching fragments from a DTA file with fragment ions from the matched precursor ion\')"
                        onmouseout="UnTip()"></input>
                        * (default: +-1.0)
                    </td>
                </tr>
                <tr><td colspan="2"><p></p></td></tr>
              </table>';
    }
}
?>
