<%@ Page Language="C#" AutoEventWireup="true"
    Inherits="CMSModules_Groups_Tools_ProjectManagement_Project_Frameset" CodeFile="Frameset.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server" enableviewstate="false">
    <title>Project properties</title>
</head>
<frameset border="0" rows="<%= TabsBreadFrameHeight %>, *" id="rowsFrameset">
    <frame name="header" src="Header.aspx<%= Request.Url.Query %>" scrolling="no" frameborder="0" noresize="noresize" />
    <frame name="content" src="../ProjectTask/List.aspx<%= Request.Url.Query %>" frameborder="5" />    
    <noframes>
		<body>
		 <p id="p1">
			This HTML frameset displays multiple Web pages. To view this frameset, use a 
			Web browser that supports HTML 4.0 and later.
		 </p>
		</body>
    </noframes>
</frameset>
</html>