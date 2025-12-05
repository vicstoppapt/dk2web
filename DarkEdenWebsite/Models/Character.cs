using DarkEdenWebsite.Enums;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Models
{
    public class Character
    {
        public int CharacterId { get; set; }
        public User Owner { get; set; }
        public string Name { get; set; }
        public Race Race { get; set; }
        public int Level { get; set; }
        public Gender Gender { get; set; }
        private List<MarketItem> _Inventory;
        public List<MarketItem> Inventory
        {
            get { return _Inventory; }
            set { _Inventory = value; }
        }
        public void AddToInventory(MarketItem item)
        {
            _Inventory.Add(item);
        }

        public override int GetHashCode()
        {
            int prime = 31;
            int result = 1;
            result = prime * result + CharacterId.BitShiftThenBitOr(prime);
            result = prime * result + Owner.GetHashCode().BitShiftThenBitOr(prime);
            result = prime * result + Level.BitShiftThenBitOr(prime);
            result = prime * result + _Inventory.ListToInt<MarketItem>().BitShiftThenBitOr(prime);
            result = prime * result + Name.StringToInt().BitShiftThenBitOr(prime);
            result = prime * result + Race.RaceToInt().BitShiftThenBitOr(prime);
            result = prime * result + Gender.GenderToInt().BitShiftThenBitOr(prime);
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
            return Name;
        }
    }
}