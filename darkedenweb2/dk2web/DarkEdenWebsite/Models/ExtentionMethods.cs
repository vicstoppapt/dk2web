using DarkEdenWebsite.Enums;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Models
{
    public static class ExtentionMethods
    {
        public static int StringToInt(this string input)
        {
            char[] chars = input.ToCharArray();
            int result = 0;
            foreach (char c in chars)
            {
                result += Convert.ToInt32(c);
            }
            return result;
        }

        public static int BitShiftThenBitOr(this int input, int prime = 31)
        {
            return (int)(input ^ (input >> prime));
        }

        public static int DateToInt(this DateTime input)
        {
            int result = int.Parse(input.ToString("yyyyMMdd"));
            return result;
        }

        public static int TimeToInt(this TimeSpan input)
        {
            int result = int.Parse(input.ToString("yyyyMMdd"));
            return result;
        }

        public static int Year(this TimeSpan time)
        {
            return time.Days / 365;
        }

        public static int GenderToInt(this Gender g)
        {
            return g == Gender.Male ? 0 : 1;
        }

        public static int AccessToInt(this Access a)
        {
            return a == Access.Allowed ? 0 : 1;
        }

        public static int MembershipToInt(this Membership m)
        {
            return m == Membership.Premium ? 0 : 1;
        }

        public static int AccountPriorityToInt(this AccountPriority a)
        {
            switch (a)
            {
                case AccountPriority.Admin:         return 0;
                case AccountPriority.GameMaster:    return 1;
                case AccountPriority.Owner:         return 2;
                case AccountPriority.Player:        return 3;
                case AccountPriority.PlayerManager: return 4;
            }
            return 0;
        }

        public static int RaceToInt(this Race r)
        {
            switch (r)
            {
                case Race.Hunter:  return 0;
                case Race.Ouster:  return 1;
                case Race.Vampire: return 2;
            }
            return 0;
        }

        public static int ListToInt<T>(this List<T> list)
        {
            int result = 0;
            foreach(T item in list)
            {
                result += item.GetHashCode();
            }
            return result;
        }
    }
}