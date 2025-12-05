using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Models
{
    public class Cart
    {
        public Cart(User Owner)
        {
            _Owner = Owner;
        }

        private User _Owner;

        public User Owner
        {
            get { return _Owner; }
        }

        private List<MarketItem> _CartItems = new List<MarketItem>();

        public List<MarketItem> CartItems
        {
            get { return _CartItems; }
            set { _CartItems = value; }
        }

        public MarketItem this[int index, int Quantity = 0]
        {
            get
            {
                int cartCount = _CartItems.Count() - 1;
                if (index > cartCount)
                {
                    _CartItems.Last().Quantity += Quantity;
                    return _CartItems.Last();
                }

                if (index < 0)
                {
                    _CartItems.First().Quantity += Quantity;
                    return _CartItems.First();
                }

                _CartItems[index].Quantity += Quantity;
                return _CartItems[index];
            }

            set
            {
                if (index >= 0)
                {
                    if (index < _CartItems.Count())
                    {
                        _CartItems[index] = value;
                        _CartItems[index].Quantity += Quantity;
                    }
                    else
                    {
                        if(_CartItems.Contains(value))
                        {
                            MarketItem temp = _CartItems.Where<MarketItem>(x => x.Equals(value)).Single<MarketItem>();
                            temp.Quantity += value.Quantity + Quantity;
                        }
                        else 
                        {
                            _CartItems.Add(value);
                            _CartItems.Where<MarketItem>(x => x.Equals(value)).Single<MarketItem>().Quantity += Quantity;
                        }
                    }
                }
            }
        }

        public void RemoveItem(int i, int amount = 0)
        {
            if (amount <= 0 || amount >= _CartItems[i].Quantity)
            {
                if (i >= 0 && i < _CartItems.Count())
                {
                    _CartItems.Remove(_CartItems[i]);
                }
            }
            else
            {
                _CartItems[i].Quantity -= amount;
            }
        }

        public int Count()
        {
            return _CartItems.Count();
        }
    }
}