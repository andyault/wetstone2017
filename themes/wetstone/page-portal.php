<?php 
	$user = wp_get_current_user(); 
	get_header();
	wp_reset_postdata();
	wp_enqueue_style( 'portal', get_template_directory_uri() . "/modal.css" );
	
	$products = get_user_meta($user->ID, 'wetstone_products', true);
	$mpsurvey = get_user_meta($user->ID, 'mpsurvey1', true);
	
	//echo $mpsurvey;
	
	$skip = false;
	
	if ($mpsurvey == "Declined") $skip = true;
	if ($mpsurvey == "Completed") $skip = true;	

	update_user_meta( $user->ID, '_last_activity', time() );
	
?>
	<section class="site-content site-content-small site-content-padded">
	<h2 class="section-header"><?php the_title(); ?></h2>
	
<?php 	if($products) {
		foreach($products as $id => $info) {
			if ($id == 633) 
				{ 	
					if (!$skip) {
?>						
<script>
const openEls = document.querySelectorAll("[data-open]");
const closeEls = document.querySelectorAll("[data-close]");
const isVisible = "is-visible";

window.addEventListener("load", function() {
    document.getElementById("modal1").classList.add(isVisible);
  });


for (const el of openEls) {
  el.addEventListener("click", function() {
    const modalId = this.dataset.open;
    document.getElementById(modalId).classList.add(isVisible);
  });
}

for (const el of closeEls) {
  el.addEventListener("click", function() {
    this.parentElement.parentElement.parentElement.classList.remove(isVisible);
  });
}

document.addEventListener("click", e => {
  if (e.target == document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
  
    if (e.target == document.getElementById("closeIt")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});

document.addEventListener("keyup", e => {
  // if we press the ESC
  if (e.key == "Escape" && document.querySelector(".modal.is-visible")) {
    document.querySelector(".modal.is-visible").classList.remove(isVisible);
  }
});

function closeIT() {
	document.querySelector(".modal.is-visible").classList.remove(isVisible);
}

function changed(thisVal, thisID) {
  if (thisVal == "") return; // please select - possibly you want something else here
	
	var elementID = "fb"+thisID.substring(8);
	
	if (thisVal < 4) {
		//*alert(elementID);
		document.getElementById(elementID).style.display='inline';
	} else {
		//*alert(elementID);
		document.getElementById(elementID).style.display='none';
	}	
} 

</script>
<div class="modal" id="modal1" data-animation="slideInOutLeft">
  <div class="modal-dialog">
    <header class="modal-header">
      <strong>A moment of your time...</strong>
      <button id="closeIt" class="close-modal" aria-label="close modal" data-close="modal1">
        X  
      </button>
    </header>
    <section class="modal-content">
      
    <div id="form_container">	
		
		<form id="mpfeedback" class="appnitro" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="form">
		<input type="hidden" name="action" value="wetstone-mpfeedback-form">
		<?php wp_nonce_field('wetstone-mpfeedback-form'); ?>
			<div class="form_description">
			<p>Your feedback is very important to us! We are always looking for ways to improve. Please take this short survey and let us know what you think of the newest version of Gargoyle Investigator™ MP.</p>
		</div>						
			<ul >
			
					
		<li id="li_2" >
		<label class="description" for="element_2">How would you rate the overall ease of installation?   </label>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_2" name="element_2" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_2" name="element_2" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_2" name="element_2" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_2" name="element_2" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_2" name="element_2" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		<div id="fb2" class="explain">
			<label class="description" for="explain_2">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_2" name="explain_2" class="element textarea small"></textarea> 
			</div>
		</div> 		
		</li>		
		<li id="li_3" >
		<label class="description" for="element_3">How would you rate the new interface?  </label>
		<div>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_3" name="element_3" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_3" name="element_3" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_3" name="element_3" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_3" name="element_3" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_3" name="element_3" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		</div>
		<div id="fb3" class="explain">
			<label class="description" for="explain_3">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_3" name="explain_3" class="element textarea small"></textarea> 
			</div>
		</div> 
		</li>		
		<li id="li_4" >
		<label class="description" for="element_4">How would you rate the scan speed?     </label>
		<div>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_4" name="element_4" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_4" name="element_4" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_4" name="element_4" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_4" name="element_4" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_4" name="element_4" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		</div> 
		<div id="fb4" class="explain">
			<label class="description" for="explain_4">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_4" name="explain_4" class="element textarea small"></textarea> 
			</div>
		</div> 
		</li>		
		<li id="li_5" >
		<label class="description" for="element_5">How would you rate the reporting options? </label>
		<div>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_5" name="element_5" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_5" name="element_5" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_5" name="element_5" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_5" name="element_5" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_5" name="element_5" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		</div>
		<div id="fb5" class="explain">
			<label class="description" for="explain_5">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_5" name="explain_5" class="element textarea small"></textarea> 
			</div>
		</div> 		
		</li>		
		<li id="li_6" >
		<label class="description" for="element_6">How would you rate the malware discovery results?</label>
		<div>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_6" name="element_6" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_6" name="element_6" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_6" name="element_6" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_6" name="element_6" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_6" name="element_6" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		</div>
		<div id="fb6" class="explain">
			<label class="description" for="explain_6">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_6" name="explain_6" class="element textarea small"></textarea> 
			</div>
		</div> 		
		</li>
		<li id="li_7" >
		<label class="description" for="element_7">How would you rate the overall satisfaction with the new product?</label>
		<div>
		<div>
			(Best) &nbsp;
			<input type="radio" id="element_7" name="element_7" value="5" onchange="changed(this.value, this.id)" /> 5 &nbsp;
			<input type="radio" id="element_7" name="element_7" value="4" onchange="changed(this.value, this.id)" /> 4 &nbsp;
			<input type="radio" id="element_7" name="element_7" value="3" onchange="changed(this.value, this.id)" /> 3 &nbsp;
			<input type="radio" id="element_7" name="element_7" value="2" onchange="changed(this.value, this.id)" /> 2 &nbsp;
			<input type="radio" id="element_7" name="element_7" value="1" onchange="changed(this.value, this.id)" /> 1 &nbsp; (Worst)
		</div>
		</div> 
		<div id="fb7" class="explain">
			<label class="description" for="explain_7">Would you like to provide more feedback?</label>
			<div>
				<textarea id="explain_7" name="explain_7" class="element textarea small"></textarea> 
			</div>
		</div> 
		</li>		
		<li id="li_1" >
		<label class="description" for="element_1">Any comments you would like to add?</label>
		<div>
			<textarea id="element_1" name="element_1" class="element textarea small"></textarea> 
		</div> 
		</li>
			
			<li class="buttons" style>
			<div style="width:500px;"
			    <input type="hidden" name="form_id" value="91553" />			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit"/>
				<input id="closeIt" class="button_text" type="button" name="later" value="Ask Me Later" style="margin: 0px 0px 0px 75px;" onclick="closeIT();"/>
				<input id="noThanks" class="button_text" type="submit" name="submit" value="Do Not Ask Again"  style="float: right" />
				</div>
			</li>
			</ul>
		</form>
		
	
	
    </section>
    <footer class="modal-footer">
      &nbsp;
    </footer>
  </div>
</div>	
<?php 
					} else {
					 //echo $mpsurvey; 
					 }						
			}
		}
	}

?>	
<table>
<tbody>
<tr>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=116"><img class="alignnone size-medium wp-image-238" src="https://www.wetstonetech.com/wp-content/uploads/2017/08/gargoyle_b-242x300.png" alt="" width="100" height="126"></a></td>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=115"><img class="alignnone size-medium wp-image-243" src="https://www.wetstonetech.com/wp-content/uploads/2019/11/StegoHuntMP-1.png" alt="" width="100" height="126"></a></td>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=123"><img class="alignnone size-medium wp-image-231" src="https://www.wetstonetech.com/wp-content/uploads/2017/08/discover_the_hidden-242x300.png" alt="" width="100" height="126"></a></td>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=121"><img class="alignnone size-medium wp-image-236" src="https://www.wetstonetech.com/wp-content/uploads/2017/08/us-latt_02-242x300.png" alt="" width="100" height="126"></a></td>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=120"><img class="alignnone size-medium wp-image-233" src="https://www.wetstonetech.com/wp-content/uploads/2017/08/ctak-1-242x300.png" alt="" width="100" height="126"></a></td>
<td><a href="https://www.wetstonetech.com/portal/my-products/?view=37"><img class="alignnone size-medium wp-image-234" src="https://www.wetstonetech.com/wp-content/uploads/2017/08/searchlight-242x300.png" alt=""width="100" height="126"></a></td>
</tr>
</tbody>
</table>
<p>Welcome to our NEW Customer Support Portal!</p>
<div>
<hr>
<p><strong>NEWS:</strong></p>
<hr>
<div><p>Our NEW Multi-Platform Malware Discovery Tool Gargoyle Investigator™ MP is here!</p>
</div>
<div>
<hr>
<p><strong>CONTACT SUPPORT/ OPERATION HOURS:&nbsp;</strong></p>
</div>
<div>
<hr>
<p>Email: support@wetstonetech.com <br /><br />
Monday thru Friday<br />
8:00 a.m. to 5:00 p.m. ET</p>
<p>WetStone observes the following holidays:<br>
New Year’s Day (1/1) <br>
Memorial Day (5/31)<br>
Independence Day (7/4)<br>
Labor Day (9/6)<br>
Thanksgiving Day (11/26)<br>
Christmas Day (12/25)</p>
<div>
<hr>
<p><strong>CUSTOMER SERVICE:</strong></p>
</div>
<div>
<hr>
<p>Contact Info/Operation Hours<br /><br />
Monday thru Friday<br />
8:00 a.m. to 5:00 p.m. ET<br />
Email: sales@wetstonetech.com</p>
</div>
<div align="left">
<hr>
<p><strong>TECHNICAL SUPPORT:</strong></p>
</div>
</div>
<div>
<hr>
<p>Our staff at WetStone is committed to providing a high level of technical support in a timely fashion. Technical support is available for customers who have a current maintenance contract that is renewed annually. If you do not reach a support representative, please leave a message or send an email and you will be contacted within 1 business day.</p>
</div>
<hr>
<div id="container">
<div id="content">
<div>
<div align="left">
<p><strong>ANNUAL SOFTWARE MAINTENANCE:</strong></p>
</div>
</div>
<div>
<hr>
<p>Each software purchase includes one year of free product maintenance. Active customers are then entitled to software upgrades for the specified contract period, access to our 24-hour customer support portal, and privy to special customer offers. Standard maintenance contracts will expire one year from your original purchase date.</p>
</div>
<div>
<hr>
<p><strong>GRACE PERIOD:</strong></p>
</div>
<div>
<hr>
<p>There is a 60 day grace period for all maintenance contracts. If the software is not renewed by the 61st day past your contract expiration date, a daily reinstatement fee will apply. Please note:</p>
<ul style="margin-left:30px; font-size: 20px; word-spacing: 3px;">
<li>Renewals made after the expiration date, but before the grace period expiration, will revert to the original expiration date.</li>
<li>Access to the support portal will be denied until the contract is renewed.</li>
</ul>
</br >
</div>
<hr>
<p><strong>FOR GARGOYLE CUSTOMERS ONLY:</span></strong></p>
<hr>
<p>Included in the annual software maintenance is the dataset subscription. This is one year of dataset updates which can be downloaded from the 24-hour customer support portal or from the automatic update feature from within the Gargoyle Investigator™ application. Customers will receive a minimum of 12 dataset updates per year and will be posted on the 15th of each month.</p>
<div>
<hr>
<p><strong>OUR GUARANTEE:</span></strong></p>
<hr>
<p>WetStone provides a 30-day money back guarantee for all product purchases. If for any reason you are not 100% satisfied with our product, you are entitled to a full refund.</p>
</div>
</div>
</div>
</section>
<?php get_footer();
?>
