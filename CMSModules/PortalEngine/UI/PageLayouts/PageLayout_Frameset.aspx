<%@ Page Language="C#" AutoEventWireup="true" CodeFile="PageLayout_Frameset.aspx.cs"
    Inherits="CMSModules_PortalEngine_UI_PageLayouts_PageLayout_Frameset" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server" enableviewstate="false">
    <title>Development - Page layouts</title>
</head>
    <frameset border="0" rows="<%= TabsBreadHeadFrameHeight %>, *" id="rowsFrameset">
    <frame name="pl_edit_menu" src="PageLayout_Header.aspx?<%=Request.QueryString %>"
        scrolling="no" frameborder="0" noresize="noresize" />
    <frame name="pl_edit_content" src="PageLayout_Edit.aspx?<%=Request.QueryString %>"
        frameborder="0" />
    <cms:NoFramesLiteral ID="ltlNoFrames" runat="server" />
</frameset>
</html>
