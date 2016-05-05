// jQuery( document ).ready(function( $ ) {
// $(function() {
$( document ).ready(function() {
	
	
	// ----------------------------------------------------------------------------
	// Declare Variables
	// ----------------------------------------------------------------------------
		
	// alert ("jQuery working?");
	
	var bInit				= true;
	var nMaxLoops			= 3;
	var nCurrentLoop		= 0;

	// ----------------------------------------------------------------------------
	// Init
	// ----------------------------------------------------------------------------	
	
	function animInit()
	{
		console.log ("[f:animInit]");

		// resetCollapseAnim ();
		// animCollapse();
		
		TweenMax.delayedCall(0.1, resetAnim);
		//TweenMax.delayedCall(0.25, mainAnim);
	
		var tlInit = new TimelineLite();
		tlInit.to("#overlay", 				1, {delay:0, opacity:0, ease:Quad.easeIn, onComplete:mainAnim});
		
		
	}
	
	animInit ();
	
	
	// ----------------------------------------------------------------------------
	// Reset Collapse
	// ----------------------------------------------------------------------------	
	
	function resetAnim ()
	{
		console.log ("[f:resetAnim]");
		// TweenMax.to("#col_star_logo", 		0, {alpha:0, onComplete:function(){/*alert("TweenMax working!") */;}});
		
		TweenMax.to("#txt1", 			0, {alpha:0});
		TweenMax.to("#txt2", 			0, {alpha:0,});
		TweenMax.to("#txt3", 			0, {alpha:0,});
		
		
		
	}
	
	
	
	// ----------------------------------------------------------------------------
	// Animation Collapse
	// ----------------------------------------------------------------------------
	
	function mainAnim ()
	{
		//create a TimelineLite instance
		console.log ("[f:mainAnim]");

		// alert ("animCollapse");
		
		if (bInit == true)
		{
			// set visiblilty of 	
			var overlay = document.getElementById('overlay');
			overlay.style.display = 'none'; // hide
			bInit = false;
		}
		

		var tlAnimCol = new TimelineLite();
	
		tlAnimCol.to("#txt1", 				1, {delay:0, opacity:1, ease:Quad.easeIn});
		tlAnimCol.to("#txt1", 				1, {delay:2.5, opacity:0, ease:Quad.easeIn});
		
		tlAnimCol.to("#txt2", 				1, {delay:0, opacity:1, ease:Quad.easeIn});
		tlAnimCol.to("#txt2", 				1, {delay:2.5, opacity:0, ease:Quad.easeIn});

		tlAnimCol.to("#txt3", 				1, {delay:0, opacity:1, ease:Quad.easeIn, onComplete:checkLoop});
		
	
		
	}
	
	// ----------------------------------------------------------------------------
	// checkLoop
	// ----------------------------------------------------------------------------
	
	
	function checkLoop ()
	{
		nCurrentLoop += 1;
		console.log ("checkLoop  nCurrentLoop = " + nCurrentLoop);
		
		
		
		if (nCurrentLoop < 3)
		{
			var tlLockup = new TimelineLite();
			tlLockup.to("#txt3", 				1, {delay:2.5, opacity:0, ease:Quad.easeOut, onComplete:mainAnim });
			
			
		} else
		{
			// end of animation
			// Stop Video
				
		}
	}
	
	
	
	
	
	

    
	// ---------- End of Script ----------
});
