using System;

using CMS.GlobalHelper;
using CMS.OnlineMarketing;
using CMS.WebAnalytics;
using CMS.SettingsProvider;

public partial class CMSModules_ContactManagement_Controls_UI_ActivityDetails_MessageBoardComment : ActivityDetail
{

    #region "Methods"

    public override bool LoadData(ActivityInfo ai)
    {
        if ((ai == null) || !ModuleEntry.IsModuleLoaded(ModuleEntry.MESSAGEBOARD))
        {
            return false;
        }

        switch (ai.ActivityType)
        {
            case PredefinedActivityType.MESSAGE_BOARD_COMMENT:
            case PredefinedActivityType.SUBSCRIPTION_MESSAGE_BOARD:
                break;
            default:
                return false;
        }

        int nodeId = ai.ActivityNodeID;
        lblDocIDVal.Text = GetLinkForDocument(nodeId, ai.ActivityCulture);

        if (ai.ActivityType == PredefinedActivityType.MESSAGE_BOARD_COMMENT)
        {
            plcComment.Visible = true;
            GeneralizedInfo iinfo = ModuleCommands.MessageBoardGetBoardMessageInfo(ai.ActivityItemDetailID);
            if (iinfo != null)
            {
                txtComment.Text = ValidationHelper.GetString(iinfo.GetValue("MessageText"), null);
            }
        }

        return true;
    }

    #endregion
}

