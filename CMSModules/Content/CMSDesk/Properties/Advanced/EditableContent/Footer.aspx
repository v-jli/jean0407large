<%@ Page Language="C#" AutoEventWireup="true" Inherits="CMSModules_Content_CMSDesk_Properties_Advanced_EditableContent_Footer"
    Theme="Default" MasterPageFile="~/CMSMasterPages/UI/EmptyPage.master" Title="Content - Footer" CodeFile="Footer.aspx.cs" %>

<asp:Content ID="cntBody" runat="server" ContentPlaceHolderID="plcContent">
    <asp:Panel runat="server" ID="pnlBody" CssClass="ContentFooter" EnableViewState="false">
        <div class="PageFooterLine">
            <cms:LocalizedButton ID="btnClose" runat="server" OnClientClick="javascript:parent.close();"
                ResourceString="general.close" CssClass="SubmitButton" Style="position: absolute;
                bottom: 10px; right: 10px;" />
        </div>
    </asp:Panel>
</asp:Content>
