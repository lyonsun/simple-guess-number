<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('guess_number');?><?php include template('common/header'); ?><p style="font-size:2em;">在下面的下拉菜单里选一个数字，看看你是不是中奖了？</p>
<br>
<select name="number">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
option
</select>
<button id="guess">就它了！</button>
<br><br>
<p id="result" style="font-size:2em;"></p>

<script src="//code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
<script>
var jq = jQuery.noConflict(); 
jq(function(){ //使用jQuery 
jq("#guess").click(function(){ 
var guess 	= jq("select[name='number']").val();
var rand 	= Math.floor(Math.random() * 10);

// alert(rand);
if (guess == rand) {
jq("#result").css('color', 'green').html('哇噻，你中奖了！');
}
else {
jq("#result").css('color', 'red').html('不好意思，这次摇奖的幸运数字是: ' + rand);
}
}); 
});
</script><?php include template('common/footer'); ?>