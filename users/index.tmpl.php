<?php require(AT_INCLUDE_PATH.'header.inc.php'); ?>

<div id="my_courses_container">
	<ul class="my-courses-list-ul" style="padding:0">

	<?php
		foreach ($this->courses as $row):

		// Harris and Armin 19.08.2010: retrieve the top level content page for each course
		$sql = 'SELECT content_id FROM '.TABLE_PREFIX."content WHERE course_id=$row[course_id] AND content_parent_id=0 AND ordering=1";
		$result = mysql_query($sql, $db);
		if ($result){
			$cp_row = mysql_fetch_assoc($result);
		}
		static $counter;
		$counter++;
	?>

		<li class="my-courses-list">
			<!-- Armin: changed call to course and point at first content element to start course right away -->
			<!-- Harris and Armin 19.08.2010 pointing at first element in the tree now -->
			<?php echo '<a href="'.url_rewrite('bounce.php?course=' . $row['course_id']).SEP. 'p='.urlencode('content.php?cid='.$cp_row['content_id']) . '"> '.htmlentities($row['title']).'</a>' ?>
		  
			<?php if ($row['last_cid']): ?>
				<a class="my-courses-resume" href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('content.php?cid='.$row['last_cid']); ?>">
					<img src="<?php echo $_base_href;  ?>themes/default/images/resume.png" border="" alt="<?php echo _AT('resume'); ?>" title="<?php echo _AT('resume'); ?>" />
				</a>
			<?php endif; ?>  

			<div class="my-courses-links">
				<?php if ($row['member_id'] != $_SESSION['member_id']  && $_config['allow_unenroll'] == 1): ?>
					<a href="users/remove_course.php?course=<?php echo $row['course_id']; ?>"><?php echo _AT('unenroll_me'); ?></a>
				<?php endif; ?>
				<?php if ($row['tests']): ?>
					<?php foreach ($row['tests'] as $test): ?>
						<a href="bounce.php?course=<?php echo $row['course_id'].SEP.'p='.urlencode('mods/_standard/tests/test_intro.php?tid='.$test['test_id']); ?>">
							<span title="<?php echo _AT('tests'); ?>:<?php echo $test['title']; ?>"><?php echo $test['title']; ?></span>
						</a> 
					 <?php endforeach ;?>
				<?php endif; ?>  
			</div>
		  
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="current_box">
	<div class="current_head">
		<h3><?php echo _AT('Recent activity'); ?></h3>
	</div>
    <?php 
				
			//display current news
			// Armin 18.08.2010 set per page maximum here in one place (important for toggeling)
 			$max_news = 2;
		
			if($_GET['p'] == 0){
			  $p = 1;
			}else{
			  $p = intval($_GET['p']);
			}
// 			if($_GET['p'] == "all"){
// 			  //$perpage = count($this->all_news);
// 			  // Armin 18.08.2010: Per page maximum of 5
// 			  $perpage = $max_news;
// 			}else{
// 			  $perpage = 2;
// 			}
// 		
// 			$newscount = count($this->all_news);
// 			// Armin 18.08.2010: Modify the recent activity so that only up to 6 itmes are displayed
// 			if ($newscount >= 4)
// 			{
// 				$newscount = 3;
// 			}
			// Armin end

			// Armin 19.08.2010: Only show n items with now ability to switch pages
			$perpage = $max_news;
			$newscount = $max_news;

			if ($perpage != 0)
			{
				$num_pages = (ceil($newscount/$perpage));;
			}
			$start = ($p-1)*$perpage;
			$end = ($p*$perpage);

			// Armin 19.08.2010 Do not print the page changer anymore
			//print_paginator($page, $num_pages, '', 1); 
			for($i=$start;$i<=$end; $i++){
				$count = $i;
				if (isset($this->all_news)) {
					echo '<ul class="recent_item">';
					if(isset($this->all_news[$i]['thumb'])){
						echo '<li"><img src="'.$this->all_news[$i]['thumb'].'" alt="'.$this->all_news[$i]['alt'].'" title="'.$this->all_news[$i]['alt'].'"/> ' . $this->all_news[$i]['link'] .' <br />';
						if($this->all_news[$i]['object']['course_id']){
						echo '<small>(<a href="bounce.php?course='.$this->all_news[$i]['object']['course_id'].'">'.$this->all_news[$i]['course'].'</a>)|';
						}
						echo '('.AT_DATE('%F %j, %g:%i',$this->all_news[$i]['time']).')</small></li>';
					}
					echo '</ul>';
				}
			}
			?>
</div>  


<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
