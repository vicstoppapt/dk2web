using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Web;

namespace DarkEdenWebsite.Models
{
    public class Update
    {

        [DisplayFormat(DataFormatString = "{0:MM-dd-yyyy}", ApplyFormatInEditMode = true)]
        public DateTime Date { get; set; }
        public string Title { get; set; }
        public string Description { get; set; }
        public UpdateType TypeOfUpdate { get; set; }
        public List<Update> TheListOfUpdates = new List<Update>();


        public Update()
        {
           
        }

        public Update(string TheTitle, DateTime TheDate, string TheDescription, UpdateType Type)
        {
            Title = TheTitle;
            Date = TheDate;
            Description = TheDescription;
            TypeOfUpdate = Type;
        }


    }
}