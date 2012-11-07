<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
  <head>
<meta content="en-us" http-equiv="Content-Language" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Settings</title>
<style type="text/css">

.auto-style3 {
	margin-left: 0px;
}
.auto-style2 {
	text-align: right;
}
.auto-style4 {
	text-align: center;
}
.auto-style5 {
	text-align: left;
}
</style>
</head>

<body>
<form>

	<table align="center" style="width: 55%; height: 214px;" class="auto-style3">
		<tr>
			<td style="height: 26px; " class="auto-style4" colspan="4">Enter the 
			time to recive questions </td>
		</tr>
		<tr>
                    <td class="auto-style5" colspan="2" style="height: 12px"><label>Enter Start time</label>
			<input name="Text1" style="width: 100px" type="text" /><select name="Select1">
			<option>AM</option>
			<option>PM</option>
			</select></td>
                        <td class="auto-style5" colspan="2" style="height: 12px"><label>Enter End time</label>
			<input name="Text2" style="width: 100px" type="text" /><select name="Select2">
			<option>AM</option>
			<option>PM</option>
			</select></td>
		</tr>
		<tr>
			<td style="width: 114px; height: 23px">Technology<input name="check1" type="checkbox" /></td>
			<td style="width: 95px; height: 23px"><label id="Label1">Programing</label><input name="check2" type="checkbox" /></td>
			<td style="height: 23px; width: 118px;"><label id="Label2">Social</label><input name="check3" type="checkbox" /></td>
			<td style="height: 23px; width: 64px;">Mobile<input name="check4" type="checkbox" /></td>
		</tr>
		<tr>
			<td style="width: 114px; height: 23px"><label id="Label3">Movies</label><input name="check5" type="checkbox" /></td>
			<td style="width: 95px; height: 23px"><label id="Label4">Games</label><input name="check6" type="checkbox" /></td>
			<td style="height: 23px; width: 118px;"><label id="Label5">Politics</label><input name="check7" type="checkbox" /></td>
			<td style="height: 23px; width: 64px;"><label id="Label6">Other</label><input name="check8" type="checkbox" /></td>
		</tr>
		<tr>
			<td style="height: 23px; azimuth:center" class="auto-style2" colspan="2">
			<input name="Reset1" type="reset" value="reset" style="width: 70px" /></td>
			<td style="height: 23px" colspan="2">
			<input name="Submit" type="submit" value="send" /></td>
		</tr>
	</table>

</form>
</body>

</html>
