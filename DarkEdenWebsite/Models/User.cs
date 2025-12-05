using DarkEdenWebsite.Enums;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Models
{
    public class User
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public Gender Gender { get; set; }
        public DateTime BirthDate { get; set; }
        public string Email { get; set; }
        public string Phone { get; set; }
        public int ZipCode { get; set; }
        public string Address { get; set; }
        public string UserName { get; set; }
        public string Password { get; set; }
        public int Pin { get; set; }
        public string LoginIP { get; set; }
        public string MacAddress { get; set; }
        public DateTime StartingDate { get; set; }
        public Access Access { get; set; }
        public AccountPriority AccountPriority { get; set; }
        public Membership MembershipType { get; set; }
        public DateTime LastLogin { get; set; }
        public DateTime LastLogout { get; set; }
        public int Age
        {
            get
            {
                TimeSpan Temp = DateTime.Now - BirthDate;
                return Temp.Year();
            }
        }
        
        public override int GetHashCode()
        {
            int prime = 31;
            int result = 1;
            result = prime * result + Id.BitShiftThenBitOr(prime);
            result = prime * result + Name.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + Gender.GenderToInt().BitShiftThenBitOr(prime);
            result = prime * result + BirthDate.DateToInt().BitShiftThenBitOr(prime);
            result = prime * result + Email.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + Phone.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + ZipCode.BitShiftThenBitOr(prime);
            result = prime * result + Address.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + UserName.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + Password.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + Pin.BitShiftThenBitOr(prime);
            result = prime * result + LoginIP.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + MacAddress.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + StartingDate.DateToInt().BitShiftThenBitOr(prime);
            result = prime * result + Access.AccessToInt().BitShiftThenBitOr(prime);
            result = prime * result + AccountPriority.AccountPriorityToInt().BitShiftThenBitOr(prime);
            result = prime * result + MembershipType.MembershipToInt().BitShiftThenBitOr(prime);
            result = prime * result + Email.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + BirthDate.ToString().StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + LastLogin.DateToInt().BitShiftThenBitOr(prime);
            result = prime * result + LastLogout.DateToInt().BitShiftThenBitOr(prime);
            result = prime * result + Age.BitShiftThenBitOr(prime);
            return result;
        }

        public override bool Equals(object obj)
        {
            if (ReferenceEquals(null, obj)) return false;
            if (ReferenceEquals(this, obj)) return true;
            return false;
        }

        public override string ToString()
        {
            return UserName;
        }
    }
}