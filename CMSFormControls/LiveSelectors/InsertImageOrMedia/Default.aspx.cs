﻿using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using CMS.UIControls;
using CMS.GlobalHelper;

public partial class CMSFormControls_LiveSelectors_InsertImageOrMedia_Default : CMSLiveModalPage
{
    protected static string mBlankUrl = null;

    protected void Page_Load(object sender, EventArgs e)
    {
        mBlankUrl = ResolveUrl("~/CMSPages/blank.htm");
    }
}
