<%@ Page Language="C#" AutoEventWireup="true"
    Inherits="CMSModules_WebAnalytics_Tools_Disabled" Theme="Default" CodeFile="Disabled.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server" enableviewstate="false">
    <title>Web analytics</title>
    <style type="text/css">
		body
		{
			margin: 0px;
			padding: 0px;
			height:100%; 
		}
	</style>
</head>
<body>
    <form id="form1" runat="server">
        <asp:Panel ID="pnlBody" runat="server" CssClass="PageBody">
            <asp:Panel ID="pnlContent" runat="server" CssClass="PageContent">
                <asp:Label runat="server" ID="lblInfo" />
            </asp:Panel>
        </asp:Panel>
    </form>
</body>
</html>
