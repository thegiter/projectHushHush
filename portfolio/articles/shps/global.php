<?php
	defined('root') or die;
	
	$thisPage = 'portfolio';
?><section class="lowlight">
	<p>
  		If the website features do not display as I describe below, then you probably don't have the latest browsers. I did not implement a browser version check to force users updating their browsers as I feel it would not be practical in production situation, because the users might just leave your website instead of doing what they should - update their browsers! Plus, browser detection is not reliable anyways.
	</p>
	<p>
  		In addition, I only use standard codes and no browser specific codes such as moz- and webkit-. Therefore, browser support for my site varies. At <?php
			require_once root.'shared/rotate_show/rotate_show.php';
			
			$rs = new rotateShowModule;
			
			$rs->text1 = 'the time of writing';
			$rs->text2_tag = 'time';
			$rs->text2_attr = 'datetime="2014-9-12"';
			$rs->text2 = '12 Sep. 2014';
			$rs->text2_abs = true;
			
			echo $rs;
		?>, I found that iPad and iPhone versions of Safari do not display my site properly, while Chrome and Firefox on desktop do (both were latest versions). And because I only develop with Firefox, support for other browsers are untested. But if they follow standard specifications, then they should be fine.
	</p>
</section>

<section>
	<h3>
      Global Features
	</h3>

	<p class="lowlight">
		Features listed here was designed for all / most of the pages.
	</p>
  
	<section>
		<h4>
			Layered Design
		</h4>
		
		<p>
			<?php
				require_once root.'shared/rotate_show/rotate_show.php';
			
				$rs = new rotateShowModule;
			
				$rs->text1 = 'Update';
				$rs->text1_abs = true;
				
				$rs->text2_tag = 'time';
				$rs->text2_attr = 'datetime="2014-11-13"';
				$rs->text2 = '13 Nov. 2014';
			
				echo $rs;
			?>: Recently, <a href="http://www.google.com/design/spec/material-design/introduction.html" title="Open the Google Material Design page." class="tgt-blank" target="_blank">Google's Material Design</a> became all over the news, and when Google does it, it's revolutionary. When I have similar ideas (my layered design), it's just lame. Of course, Google can not possibly have stolen this idea from me, but I like to think that I and the Google designers are like-minded people. We both try to give rules to our design elements.
		</p>

		<figure id="ld-img-cnr" class="img-resp-cnr larg-scrn-min-3">
			<div id="ld-img" class="inset-img">
			</div>
  		</figure>
      
		<section class="clr-dbl-aft" title="Text Content">
			<p>
              The whole design is separated into mainly 3 layers to give contents semantic meanings.
			</p>
			<p>
              <mark>The middle layer</mark> is the ground level. It serves as the general background of the website. It has the least meaning but is, nonetheless, a huge and important part of the website.
			</p>
			<p>
              <mark>The bottom layer</mark> is the underground level. This level stands out from the background by sinking into the ground. It marks out an area for special purposes by carving out a hole in the ground. Contents in the hole are generally items that are not part of the background but are more or less a common sight. Such as navigation menus and breadcrumbs. These are special things but shouldn't be catching too much of the user's attentions.
			</p>
			<p>
              <mark>The top layer</mark> is the foreground layer which has a special usage. Aside from making things stand out and catching people's attentions, it also allows me to put content where the space is already occupied by an underground layer. This layer is rarely used.
			</p>
			<p>
              As such, the 3 layered design conserves space without seems to crowd the page with too much content clustered next to each other. Each layer can then have its sub-layers to make more complex designs.
			</p>
			<p>
              To achieved the 3 layered design, a lot of shadows was used. Unfortunately, when I started this site, I was still learning the basics, and if I remember correctly, CSS shadows were still an experimental technology. So all the shadows were pictures created with Photoshop, then sliced and positioned onto the elements. They were sliced so they can adapt to different sizes of elements. It was a lot of hard work. Now I try to use CSS shadows whenever I can. Things are easier now, but all the effort I put into making those picture shadows feels kinda wasted.
			</p>
		</section>
	</section>
	<section>
		<h4 class="ta-rgt">
			Animated Navigation Bar
		</h4>
      
		<section class="inset-sdw" title="Sample Demonstration">
			<?php
				require root.'shared/lv2/menu/menu.php';
			?>
			
		</section>
      
		<section title="Text Content">
			<p>
				When I began developing this little module, mobile devices were still mainly used for phone calls. They were capable of viewing webpages, especially the expensive ones, but I would not say that it was common practice.
			</p>
			<p>
				I have to admit that I was short-sighted. I felt that mobile devices, with their tiny screens, seriously limit a developer's creativty. So much constraints and limitations that I rejected the idea of making my site mobile compatible. Little did I know that now there is a growing demand for responsive design.
			</p>
			<p>
				As a result, the menu module for my site is not designed with mobile devices in mind. It's got a horizontal layout that is not fluid, i.e. can not adapt to smaller screens, and its animation requires a mouse over event to trigger. And since there are no mouses on cellphones...
			</p>
			<p>
				However, it works perfectly on computers. It uses the underground layer to highlight the current menu item, then when you hover your mouse over other menu items, the apparent "hole in the ground" would move itself to highlight your selection, adjusting its width to fit the new menu item. It also doesn't just move in a linear motion, i.e. same motion and speed. Depend on the distance, it will move faster or slower, and when reaching its destination, it will dash out a little bit, simulating an inertia effect.
			</p>
			<p>
				Overall, it's not an uncommon design that I haven't seen in other sites, but I did spend a lot of time trying to figure it out. Sadly, it's an outdated design now.
			</p>
		</section>
	</section>
</section>