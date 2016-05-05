$(function() {

	

// ----------------------------------------------------------------------------
// Declare Variables
// ----------------------------------------------------------------------------


var nMaxLoop				= 3;
var nLoopCount				= 0;


//alert ("MAIN ANIMATION");
	
// --------------------------------------------------
//Function to run with any animations starting on load, or bringing in images etc
// --------------------------------------------------

$("#audi_logo").css("opactity", "0.5");
	
		//TweenLite.to("#audi_logo", 5, {y:500});


// ------------------------------------------------------------
// Init Banner
// ------------------------------------------------------------
	

function initBanner ()
{
	
	//TweenLite.to("#audi_logo", 0, {alpha:0});
	TweenLite.to("#audi_a5_mud", 0, {alpha:0});
	
	TweenLite.to("#txt1_line1", 0, {alpha:0});
	TweenLite.to("#txt1_line2", 0, {alpha:0});
	TweenLite.to("#txt2", 0, {alpha:0});
	TweenLite.to("#cta", 0, {alpha:0});
	
	mainAnimation ();
		
		
}
initBanner ();



// ------------------------------------------------------------
// main Animation
// ------------------------------------------------------------

function mainAnimation () {
	
	var del = 0;
	
	
	
	var tlAnim = new TimelineLite();
	tlAnim.to("#overlay", 2, 			{delay:0, alpha:0, ease:Quad.easeOut});
	tlAnim.to("#txt1_line1", 2, 		{delay:0, alpha:1, ease:Quad.easeOut}, "=-1");
	tlAnim.to("#audi_a5_mud", 2, 		{delay:0, alpha:1, ease:Quad.easeOut});
	tlAnim.to("#txt1_line2", 2, 		{delay:0, alpha:1, ease:Quad.easeOut}, "=-2");

	
	tlAnim.to("#txt1_line1", 1, 		{delay:0, alpha:0, ease:Quad.easeOut}, "=2");
	tlAnim.to("#txt1_line2", 1, 		{delay:0, alpha:0, ease:Quad.easeOut}, "=-1");
	
	tlAnim.to("#audi_a5_mud", 2, 		{delay:0, alpha:0, ease:Quad.easeOut}, "=-1");
	tlAnim.to("#txt2", 2, 				{delay:0, alpha:1, ease:Quad.easeOut}, "=-1");

	tlAnim.to("#cta", 2, 				{delay:0, alpha:1, ease:Quad.easeOut, onComplete:endofbanner});
	
	function endofbanner () {
		
		TweenLite.delayedCall(3, checkLoop );

	}

}

// ------------------------------------------------------------
// CheckLoop
// ------------------------------------------------------------


function checkLoop () {
	// alert ("End of Anim");
	
	nLoopCount = nLoopCount +1;
	
	if (nLoopCount >= nMaxLoop) {
		// End of Animation	

		console.log ("end of animation / nLoopCount = " + nLoopCount);
	} else {
		
		console.log ("nLoopCount = " + nLoopCount);
		
		TweenLite.to("#overlay", 2, { alpha:1, onComplete:initBanner});
		

		
	}

	
	
	
}
	


	
	


	// ---------- End of Script ----------
});




