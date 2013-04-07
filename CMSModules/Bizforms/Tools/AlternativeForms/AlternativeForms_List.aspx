<%@ Page Language="C#" AutoEventWireup="true" MasterPageFile="~/CMSMasterPages/UI/SimplePage.master"
    Title="Alternative Forms List" Inherits="CMSModules_BizForms_Tools_AlternativeForms_AlternativeForms_List"
    Theme="Default" CodeFile="AlternativeForms_List.aspx.cs" %>

<%@ Register Src="~/CMSModules/AdminControls/Controls/Class/AlternativeFormList.ascx"
    TagName="AlternativeFormList" TagPrefix="cms" %>
<asp:Content ID="cntBody" runat="server" ContentPlaceHolderID="plcContent">
    <asp:Label ID="lblError" runat="server" CssClass="ErrorLabel" EnableViewState="false"
        Visible="false" />
    <cms:AlternativeFormList ID="listElem" runat="server" IsLiveSite="false" />
</asp:Content>
