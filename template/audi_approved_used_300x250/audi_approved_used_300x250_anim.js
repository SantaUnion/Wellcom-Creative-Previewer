$(function() {

	

// ----------------------------------------------------------------------------
// Declare Variables
// ----------------------------------------------------------------------------


var nMaxLoop				= 3;
var nLoopCount				= 0;
   

// ----------------------------------------------------------------------------
// Clickthru
// ----------------------------------------------------------------------------
 
$('#background_exit_dc').click(function(){
    // location.href='http://www.audi.co.uk/audi/used-cars/audi-approved-used.html'
	window.open('http://www.audi.co.uk/audi/used-cars/audi-approved-used.html');
})
    
    
// ------------------------------------------------------------
// Init Banner
// ------------------------------------------------------------
	

function initBanner ()
{

	//TweenLite.to("#audi_logo", 0, {alpha:0});
	TweenLite.to("#car", 0, {scaleX:0.44, scaleY:0.44});
	
	TweenLite.to("#txt1", 0, {alpha:0});
	TweenLite.to("#txt2", 0, {alpha:0});
	TweenLite.to("#txt3", 0, {alpha:0});
	TweenLite.to("#cta", 0, {alpha:0});
	TweenLite.to("#tandc", 0, {alpha:0});
	
	mainAnimation ();
		
		
}
initBanner ();



// ------------------------------------------------------------
// main Animation
// ------------------------------------------------------------

function mainAnimation () {
	
	var del = 0;
	
	var tlAnim = new TimelineLite();
	tlAnim.to("#overlay", 2, 		{delay:0, alpha:0, ease:Quad.easeOut});
	
	// 
	tlAnim.to("#car", 1, 			{delay:0, scale:0.6, y:18, z:0.01, transformOrigin:"144px 104px", ease:Quad.easeInOut});
	tlAnim.to("#txt1", 1, 			{delay:0, alpha:1, ease:Quad.easeOut}, "=0");
	
	
	tlAnim.to("#txt1", 1, 			{delay:0, alpha:0, ease:Quad.easeOut}, "=2");
	
	tlAnim.to("#car", 2, 			{delay:0, scale:1, y:55, z:0.01, transformOrigin:"144px 104px", ease:Quad.easeInOut}, "=-0.5");
	tlAnim.to("#txt2", 2, 			{delay:0, alpha:1, ease:Quad.easeOut}, "=-0.5");
	
	
	tlAnim.to("#txt2", 1, 			{delay:0, alpha:0, ease:Quad.easeOut}, "=2");
	tlAnim.to("#car", 1, 			{delay:0, alpha:0, ease:Quad.easeOut}, "=-1");
	
	
	tlAnim.to("#txt3", 2, 			{delay:0, alpha:1, ease:Quad.easeOut}, "=0");
	tlAnim.to("#cta", 2, 			{delay:0, alpha:1, ease:Quad.easeOut}, "=-1");
	tlAnim.to("#tandc", 2, 			{delay:0, alpha:1, ease:Quad.easeOut }, "=-1");
	
	
	
	
	
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




