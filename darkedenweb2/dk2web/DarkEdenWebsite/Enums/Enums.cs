using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Enums
{
    public enum Gender
    {
        Male, Female
    }

    public enum AccountPriority 
    {
        Owner, Admin, GameMaster, PlayerManager, Player
    }

    public enum Access
    {
        Allowed, Disallowed
    }

    public enum Membership 
    {
        Premium, NonPremium
    }

    public enum Race
    {
        Vampire, Hunter, Ouster, Common
    }
}