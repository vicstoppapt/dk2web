using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using DarkEdenWebsite.Models;

namespace DarkEdenWebsite.Controllers
{
    public class HomeController : Controller
    {
        private Update update = new Update();
        private List<Update> homeUps = new List<Update> { new Update { Date = new DateTime(2015,5,3) , Title = "New Changes", Description = "We have changed a few of the layouts on the page and the backgrounds, but nothing too crazy has changed. You will survive, so don't panic. Carry on!" + 
            "The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will, shepherds the weak through the valley of darkness, for he is truly his brother's keeper and the finder of lost children.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,5,10), Title = "Vamp Marathon", Description = "You see? It's curious. Ted did figure it out - time travel. And when we get back, we gonna tell everyone. How it's possible, how it's done, what the dangers are." + 
                                                      " But then why fifty years in the future when the spacecraft encounters a black hole does the computer call it an 'unknown entry event'? Why don't they know? If they don't know, that means we never told anyone." + 
                                                      " And if we never told anyone it means we never made it back. Hence we die down here. Just as a matter of deductive logic.", TypeOfUpdate = UpdateType.Events},
                                                  new Update { Date = new DateTime(2015,5,23) , Title = "Do You Love the Game?", Description = "We have changed a few of the layouts on the page and the backgrounds, but nothing too crazy has changed. You will survive, so don't panic. Carry on!" + 
            "The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will," + 
            " shepherds the weak through the valley of darkness, for he is truly his brother's keeper and the finder of lost children.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,5,31), Title = "LETS PLAY", Description = "You see? It's curious. Ted did figure it out - time travel. And when we get back, we gonna tell everyone. How it's possible, how it's done, what the dangers are." + 
                                                      " But then why fifty years in the future when the spacecraft encounters a black hole does the computer call it an 'unknown entry event'? Why don't they know? If they don't know, that means we never told anyone." + 
                                                      " And if we never told anyone it means we never made it back. Hence we die down here. Just as a matter of deductive logic.", TypeOfUpdate = UpdateType.Events},
                                               
                                                };
        private List<Update> ups = new List<Update> { new Update { Date = new DateTime(2015,5,3) , Title = "New Changes", Description = "We have changed a few of the layouts on the page and the backgrounds, but nothing too crazy has changed. You will survive, so don't panic. Carry on!" + 
            "The path of the righteous man is beset on all sides by the iniquities of the selfish and the tyranny of evil men. Blessed is he who, in the name of charity and good will," + 
            " shepherds the weak through the valley of darkness, for he is truly his brother's keeper and the finder of lost children.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,6,1), Title = "Vamp Marathon", Description = "We are hosting a Vamp Marathon where all vampires in game can earn items based on actions.", TypeOfUpdate = UpdateType.Events},
                                                  new Update { Date = new DateTime(2015,5,12) , Title = "Market Updates", Description = "You think water moves fast? You should see ice. It moves like it has a mind. Like it knows it killed the world once and got a taste for murder.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,5,15) , Title = "New Items in Market", Description = "Normally, both your assets would be dead as freaking fried chicken, but you happen to pull this crap while I'm in a transitional period so I don't wanna kill you," + 
                                                      " I wanna help you. But I can't give you this case, it don't belong to me. Besides, I've already been through too much crap this morning over this case to hand it over to your dumb assets.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,5,20) , Title = "Servers??", Description = "After the avalanche, it took us a week to climb out. Now, I don't know exactly when we turned on each other, but I know that seven of us survived the slide... and only five made it out. Now we took an oath, that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't. Nature is lethal but it doesn't hold a candle to man.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,6,1), Title = "New Options", Description = "Like you, I used to think the world was this great place where everybody lived by the same standards I did, then some kid with a nail showed me I was living in his world," + 
                                                      " a world where chaos rules not order, a world where righteousness is not rewarded.", TypeOfUpdate = UpdateType.News},
                                                  new Update { Date = new DateTime(2015,6,5), Title = "Slayer Marathon", Description = "Do you see any Teletubbies in here? Do you see a slender plastic tag clipped to my shirt with my name printed on it?" + 
                                                      " Do you see a little Asian child with a blank expression on his face sitting outside on a mechanical helicopter that shakes when you put quarters in it? No? Well, that's what you see at a toy store." +  
                                                      "And you must think you're in a toy store, because you're here shopping for an infant named Jeb.", TypeOfUpdate = UpdateType.Events},
                                                  new Update { Date = new DateTime(2015,6,1), Title = "Free Play - Free Gold", Description = "That's Cesar's world, and if you're not willing to play by his rules, then you're gonna have to pay the price.", TypeOfUpdate = UpdateType.Events},
                                                  new Update { Date = new DateTime(2015,6,1), Title = "Who's Your Fav?", Description = "Yeah, I like animals better than people sometimes... Especially dogs. Dogs are the best. Every time you come home, they act like they haven't seen you in a year." + 
                                                      " And the good thing about dogs... is they got different dogs for different people. Like pit bulls. The dog of dogs. Pit bull can be the right man's best friend... or the wrong man's worst enemy." + 
                                                      "You going to give me a dog for a pet, give me a pit bull. Give me... Raoul. Right, Omar? Give me Raoul.", TypeOfUpdate = UpdateType.Events},
                     
                                                 };


        // GET: Home
        public ActionResult Index()
        {
            foreach (var i in homeUps)
            {
                update.TheListOfUpdates.Add(i);
            }

            return View(update);
        }

        public ActionResult Market()
        {
            return View();
        }

        public ActionResult NewsEvents()
        {
            foreach (var i in ups)
            {
                update.TheListOfUpdates.Add(i);
            }
            return View(update);
        }

        public ActionResult Info()
        {
            return View();
        }


    }
}