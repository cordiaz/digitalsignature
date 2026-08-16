<html>
<head>
<title>Pencarian</title>
			<script type="text/javascript"> 
function alphanumeric(txt) 
{ 
var letters = /^[0-9a-zA-Z]+$/; 
if(txt.value.match(letters)) 
{ 
alert('Your registration is correct '); 
document.formcari.name.focus(); 
return true; 
} 
else 
{ 
alert('Please input alphanumeric characters only'); 
return false; 
} 
} 
</script>
</head>
	<style>
.content {
  max-width: 640px;
  margin: auto;
}
</style>
<body>

	<div class="content">
		<h2>Cari Detail Digital Signature</h2>
		<hr size="1">
		<form name="formcari" method="post" action="search-engine.php">
<table>
<tr><td>Kode</td>
	<td><input type="text" name="name" id='nama'><br><i>case censitive alpah numeric 8 digits</i></td>
</tr>
	<tr>
<td><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="search" onclick="alphanumeric(document.formcari.name)"></td>
	<td></td>
	</tr>
</table>
</form>
		</div>
</body>
</html>