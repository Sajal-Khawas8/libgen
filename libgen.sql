-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2023 at 03:57 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libgen`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_uuid` varchar(40) NOT NULL DEFAULT uuid(),
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `cover` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `rent` int(11) NOT NULL,
  `fine` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_uuid`, `title`, `author`, `description`, `cover`, `category_id`, `rent`, `fine`, `active`, `creation_date`, `modification_date`) VALUES
(17, '435aae16-a32b-11ee-a3ac-1ce422fe95f0', 'Shrimad Bhagavad Gita As It Is', 'Swami Prabhupada', 'The largest-selling latest edition of the Bhagavad-gita in Hindi, is knowledge of 5 basic truths and the relationship of each truth to the other: These five truths are Krishna, or God, the individual soul, the material world, action in this world, and time. In translating the Gita, A. C. Bhaktivedanta Swami Prabhupada has remained loyal to the intended meaning of Krishna&#039;s words, and has unlocked all the secrets of the ancient knowledge of the Gita and placed them before us as an exciting opportunity for self-improvement and spiritual fulfillment. The Gita is a conversation between Krishna and His dear friend Arjuna. At the last moment before entering a battle between brothers and friends, the great warrior Arjuna begins to wonder: Why should he fight? What is the meaning of his life? Where is he going after death? In response, Krishna brings His friend from perplexity to spiritual enlightenment, and each one of us is invited to walk the same path. About A. C. Bhaktivedanta Swami Prabhupada Abhay Charanaravinda Bhaktivedanta Swami Prabhupada was a Gaudiya Vaishnava guru and the founder-acharya of the Hare Krishna Movement. His mission has reached the entire world and his teachings are followed by millions of people. He is also the founder of the International Society For Krishna Consciousness (ISKCON). His publications include an unabridged translation of the Srimad Bhagavatham in, the 18 cantos, the Isopanishad and a shorter version of the Bhagavad Gita.', 'http://localhost/libgen/assets/uploads/images/books/6589867660f6c.webp', 6, 1, 2, 1, '2023-12-25 14:41:10', '2023-12-25 14:41:10'),
(18, '1ab911ec-a32c-11ee-a3ac-1ce422fe95f0', 'Three Men In A Boat', 'Jerome K. Jerome', 'Three Men In A Boat is a comic memoir of a boating holiday on the Thames between Kingston and Oxford by Jerome K. Jerome.\r\n\r\nSummary of the Book\r\n\r\nThree men decide that they have been overworked for quite some time and decide to take a uneventful boating holiday. George, Harris, Jerome and Montmorency, a fox terrier, set forth on a boating trip up the River Thames, from Kingston upon Thames to Oxford, during which they plan to camp, despite Jerome&#039;s admission about previous experiences with tents and camping stoves. The comic story of what ensues forms the rest of the novel which involves the three friends and their fortunes and misfortunes. The events show the ridiculous unreliability of barometers for weather forecasting to the difficulties encountered when learning to play the Scottish bagpipe, and soon the three friends discover that their trip might not be that uneventful after all.\r\n\r\nAbout Jerome K. Jerome\r\n\r\nJerome K. Jerome was an English writer, best remembered for Idle Thoughts of an Idle Fellow, Second Thoughts of an Idle Fellow, and Three Men on the Bummel.\r\n\r\nThree Men On A Boat was adapted into several major motion pictures, including a made-for-television version by the BBC in 1975, starring Tim Curry and Stephen Moore.', 'http://localhost/libgen/assets/uploads/images/books/658987dfac793.webp', 5, 2, 3, 1, '2023-12-25 14:47:11', '2023-12-25 14:47:11'),
(19, '50823d2b-a32c-11ee-a3ac-1ce422fe95f0', 'The Invisible Man', 'H.G. Wells', 'From the twentieth century&#039;s first great practitioner of the novel of ideas comes a consummate masterpiece of science fiction about a man trapped in the terror of his own creation. First published in 1897, The Invisible Man ranks as one of the most famous scientific fantasies ever written. Part of a series of pseudoscientific romances written by H. G. Wells (1866–1946) early in his career, the novel helped establish the British author as one of the first and best writers of science fiction. Wells&#039; years as a science student undoubtedly inspired a number of his early works, including this strikingly original novel. Set in turn-of-the-century England, the story focuses on Griffin, a scientist who has discovered the means to make himself invisible. His initial, almost comedic, adventures are soon overshadowed by the bizarre streak of terror he unleashes upon the inhabitants of a small village. Notable for its sheer invention, suspense, and psychological nuance, The Invisible Man continues to enthrall science-fiction fans today as it did the reading public nearly 100 years ago.', 'http://localhost/libgen/assets/uploads/images/books/65898839eb3a1.webp', 7, 2, 4, 1, '2023-12-25 14:48:41', '2023-12-25 14:48:41'),
(20, '8a0131f0-a32d-11ee-a3ac-1ce422fe95f0', 'Karma', 'Sadguru', '&quot;Full of valuable insights to guide you.&quot;-WILL SMITH &quot;Thoughtful and life-affirming . . . a must-read.&quot;-TONY ROBBINS &quot;Forget what you think you know about karma-Sadhguru shows us it&#039;s not a punishment for bad behavior, but a vehicle for transformation and empowerment. This book will put you back in charge of your own life.&quot;-Tom Brady &quot;Pursuing your truth. Understanding this human experience. Embodying the divine is such an ongoing process of unveiling, adapting, and redesigning. The words in this book are the key to unlocking your truth, to see with no eyes, to hear the truth that lies in silence, and to connect with your inner wisdom. Thank you, Sadhguru, for such an enlightening creation, an offering to all seekers.&quot;-HRH Princess Noor bint Asem of Jordan &quot;At last, a book about karma that can be trusted. I have never found a book that explains-and solves-the mystery of karma with the simplicity, clarity, and hopefulness of this invaluable book.&quot;-Deepak Chopra &quot;Sadhguru here offers an easy read on a difficult subject: karma, or the volition to perform action. A truly captivating view from a renowned yogi and mystic on free will and the destiny of the human mind.&quot;-Prof. Dr. Steven Laureys, neurologist, University Hospital of Liège, Belgium &quot;The tools Sadhguru provides in Karma bring me to a place of peace within myself. Thank you for your wisdom and transformational guidance.&quot;-Rosanna Arquette &quot;In Karma, Sadhguru brilliantly demystifies the concept of karma and how we can harness our perceptions to change our own futures and, in doing so, create a more sustainable, just, and spiritually enlightened world. If you want to be the change you want to see in the world, read Karma and begin the journey.&quot;-Terry Tamminen, Secretary of the California Environmental Protection Agency for Governor Arnold Schwarzenegger &quot;This five-letter word that has baffled humans for thousands of years is finally explained in 272 pages. It&#039;s a compass for navigating life. Thank you, Sadhguru.&quot;-Jay Naidoo, Minister in President Nelson Mandela&#039;s cabinet, recipient of the Chevalier de la Légion d&#039;Honneur, France A much-used word, Karma is loosely understood as a system of checks and balances in our lives, of good actions and bad deeds, of good thoughts and bad intentions. A system which seemingly ensures that at the end of the day one gets what one deserves. This grossly over-simplified understanding has created many complexities in our lives and taken away from us the very fundamentals of the joy of living. Through this book, not only does Sadhguru explain what Karma is and how we can use its concepts to enhance our lives, he also tells us about the Sutras, a step-by-step self help &amp; self improvement guide to navigating our way in this challenging world. In the process, we get a deeper, richer understanding of life and the power to craft our destinies.', 'http://localhost/libgen/assets/uploads/images/books/65898a47df0fe.webp', 6, 1, 2, 1, '2023-12-25 14:57:27', '2023-12-25 14:57:27'),
(21, 'f69494dc-a32d-11ee-a3ac-1ce422fe95f0', 'A Brief History Of Time', 'Stephen Hawking', 'A Brief History of Time, authored by the legendary theoretical physicist Stephen Hawking, is considered to be the holy grail of populalizing scientific writing and ever since it was published for the first time in 1988, the book has been an ultimate guide to both scientific and non-scientific communities searching for answers to the most fundamental questions.\r\n\r\nSummary of the Book\r\n\r\nUnarguably one of the greatest minds living on the planet, Stephen Hawking takes the reader to a breath taking journey into the depths of cosmos from big bang to big crunch, from nature of forces to the corners of multiple dimensions, from quarks to the entangled theory of strings, through the wormholes, encircling event horizons, along the arrow of time and ultimately leaving him on the edge of the universe with his ideas on the grand unification of forces – the theory of everything. Hawking being a brilliant physicist draws a stunningly simple anatomy of intricate nature of the universe through an elegantly written language of physics and mathematics and thus making even a non-scientific person understanding some of the most important aspects of working nature of the cosmos. By stimulating the existential inquisitiveness of the reader, A Brief History of Time not only awakens his interests in physics, cosmology, and particle physics but inspires the natural philosopher and the historian in him. Following the legacy of an elite bunch of people such as Kepler, Copernicus, Einstein and Sagan who brought science of universe to the general public, Hawking successfully attempts in bringing topics of black holes and wormholes to the tables of cafeterias and public media. A Brief History of Time is timeless in its own nature and deserves to be read by anyone who has a curiosity to know the origin and fate of his or her own existence.\r\n\r\nAbout the Author\r\n\r\nStephen Hawking is considered to be one of the greatest living minds existing. Born to an English family on the 300th death anniversary of Galileo, Stephen Hawking has his alma mater in the Oxford and Trinity Hall, Cambridge. After an extensive work in physics and cosmology, in his early age itself he was showing a great potential in becoming one of the leading scientists in the field. When he was 21 he was diagnosed with Amyotrophic Lateral Sclerosis, a progressive motor neuron disease which destroyed the ability of his muscles to move and respond. It deteriorated with time and resulting in losing almost complete capability to move his body, but his brain continued to marvel with his extraordinary theories and thoughts. In 1974 he stunned the scientific community with his thesis on black holes, now famously called as Hawking Radiation.  In 1979 he was honored with the Lucasian Professor of Mathematics at Cambridge, which has been the epitome of all the scientific achievements. Even though he had reached a state where he needed a computerized speech synthesizer to articulate his thoughts, he wrote the magnum opus of science, A Brief History of Time which has sold more than 10 million copies. He currently holds the position of Director of Research at the Centre for Theoretical Cosmology within the University of Cambridge. Few of other books authored by him are The Universe in a Nutshell, The Grand Design, My Brief History, The Theory of Everything and The Nature of Space and Time.', 'http://localhost/libgen/assets/uploads/images/books/65898afe13ce3.webp', 8, 3, 5, 1, '2023-12-25 15:00:30', '2023-12-25 15:00:30'),
(22, '83db4b7b-a32e-11ee-a3ac-1ce422fe95f0', 'Relativity', 'Einstein Albert', 'Albert Einstein&#039;s Relativity: The Special and the General Theory is a cornerstone of modern physics. Einstein intended this book for &quot;those readers who, from a general scientific and philosophical point of view, are interested in the theory, but who are not conversant with the mathematical apparatus.&quot; Indeed, within the vast literature on the philosophy of space and time, Einstein&#039;s Relativity shall remain and illuminable and intelligible exposition, highly quotable as one of the most lucid presentations of the subject matter, and a launching pad for any further inquiry on the fascinating features of our universe.', 'http://localhost/libgen/assets/uploads/images/books/65898beb13048.webp', 8, 3, 5, 1, '2023-12-25 15:04:27', '2023-12-25 15:04:27'),
(23, 'ca8c2049-a32e-11ee-a3ac-1ce422fe95f0', 'The Complete Novel Of Sherlock Holmes', 'Doyle Arthur Conan', 'Summary of the Book\r\n\r\nThis book is a compilation of the four famous Sherlock Holmes novels known as A Study in Scarlet, The Sign of the Four, The Hound of the Baskervilles and The Valley of Fear. Sherlock Holmes has been one of the most beloved fictional characters ever created. Now enjoy the four crime novels in a single book. You will experience suspense, thrill and mystery - everything that made Sherlock Holmes stories a favourite.\r\n\r\nAbout the Author\r\n\r\nThe master creator of Sherlock Holmes and many other short stories - Sir Arthur Ignatius Conan Doyle was a prolific British writer. His Sherlock Holmes stories are considered milestones in the field of crime fiction. Doyle’s non-Sherlockian works include fantasy and science fiction stories about Professor Challenger and humorous stories about the Napoleonic soldier Brigadier Gerard, as well as plays, romances, poetry, non-fiction and historical novels.', 'http://localhost/libgen/assets/uploads/images/books/65898cb4da690.webp', 7, 4, 6, 1, '2023-12-25 15:06:25', '2023-12-25 15:07:48'),
(24, '6de92ae3-a32f-11ee-a3ac-1ce422fe95f0', 'Alice In Wonderland', 'Lewis Carroll', 'One summer afternoon, little Alice is lounging in the garden when she spots a white rabbit wearing a waistcoat and carrying a pocket watch! As the rabbit scurries by, muttering this and that, Alice jumps up and runs after him. Following the peculiar creature down a rabbit hole, Alice suddenly finds herself in an endless fall! Thus starts an exciting and bizarre adventure as Alice meets weird creatures like the White Rabbit and Cheshire Cat. Set out on an adventure with Alice, that unravels for you, many astonishing and hilarious escapades in Wonderland!', 'http://localhost/libgen/assets/uploads/images/books/65898d73bea75.webp', 5, 2, 4, 1, '2023-12-25 15:10:59', '2023-12-25 15:10:59'),
(25, 'be2723ba-a32f-11ee-a3ac-1ce422fe95f0', 'The Jungle Book', 'Rudyard Kipling', 'Saved from the clutches of the tiger Shere Khan, a lost little boy named Mowgli is taken in by a family of wolves in the forest. As the human child grows up under the loving care of his wolf parents and brothers, he develops a special bond with Baloo the bear, Bagheera the panther, and Akela, the leader of his wolf-pack. Journey with Mowgli on his adventures and capers with the animals in the forest—both friendly and dangerous—as he fights to escape the evil tiger once again, while trying to reconcile his human roots with his wolf-upbringing!', 'http://localhost/libgen/assets/uploads/images/books/65898dfa63016.webp', 5, 2, 4, 1, '2023-12-25 15:13:14', '2023-12-25 15:13:14'),
(26, '09dfcf1c-a330-11ee-a3ac-1ce422fe95f0', 'Pride And Prejudice', 'Austen Jane', 'Pride and Prejudice is a particularly interesting and comical read for fans of Jane Austen. The romantic comedy is one of the author’s best works and revolves around the growing relationship between Elizabeth Bennet and Fitzwilliam Darcy.\r\n\r\nSummary of the Book\r\n\r\nElizabeth is an intelligent and lively woman who has four beautiful sisters. Their mother’s only wish is for her daughters to be married off to wealthy and reputable men. Elizabeth meets the proud Darcy at a party that is taking place near her home. Her first thoughts about him are that he is snooty and so decides that she wants nothing to do with him. However, they get talking and, as time goes, realize that each of them has started to grow more and more tolerant to each other. The story reveals a lot about the society of the time in which the story is set.\r\n\r\nAbout Jane Austen\r\n\r\nJane Austen was an English novelist, best remembered for her works of romance literature. She has also written: Emma, Persuasion, Mansfield Park, and Sense and Sensibility.\r\n\r\nPride and Prejudice was adapted into several films, most notably in 1940 starring Greer Garson and Laurence Olivier, and in 2005 starring Keira Knightley and Matthew Macfadyen.', 'http://localhost/libgen/assets/uploads/images/books/65898e796c681.webp', 7, 3, 5, 1, '2023-12-25 15:15:21', '2023-12-25 15:15:21'),
(27, '60ddf41f-a330-11ee-a3ac-1ce422fe95f0', 'The Wind On Haunted Hill', 'Ruskin Bond', '&#039;Who . . . whoo . . . whooo, cried the wind as it swept down from the Himalayan snows.&#039; The wild wind pushes open windows, chokes chimneys and blows away clothes as it huffs and puffs over the village by Haunted Hill, where Usha, Suresh and Binya live. It&#039;s even more mighty the day Usha is on her way back from the bazaar. A deep rumble echoes down the slope and a sudden flash of lightning lights up the valley as fat drops come raining down. In search of shelter, Usha rushes into the ruins on Haunted Hill, grim and creepy against the dark sky. Inside, the tin roof groans, strange shadows are thrown against the walls and little Usha shivers with fear. For she isn&#039;t alone. A gritty, hair-raising story about friendship, courage and survival, this stunning edition will introduce another lot of young readers to the magic of Ruskin Bond&#039;s craft.', 'http://localhost/libgen/assets/uploads/images/books/65898f0b60530.webp', 7, 2, 5, 1, '2023-12-25 15:17:47', '2023-12-25 15:17:47'),
(28, 'b284acca-a330-11ee-a3ac-1ce422fe95f0', 'The Blue Umbrella', 'Ruskin Bond', 'The Blue Umbrella is a stirring novel about the simple yet generous minds of children by the author Ruskin Bond.\r\n\r\nSummary Of The book\r\n\r\nThe Blue Umbrella is a novel set in the mountainous Gharwal region. The story is about a little girl called Binya, who on falling upon a group of picnickers, is enchanted by a beautiful blue umbrella belonging to one of them. Incidentally, one of the ladies in the group takes a liking to Binya’s leopard claw necklace and offers to buy it from her. When Binya refuses, she is asked to choose anything that she would like in return for it and she chooses the blue umbrella. After much pleading, the lady consents to her wish and Binya becomes the proud owner of the umbrella.\r\n\r\nBut, what happens when the village shopkeeper sets his eyes on the very same umbrella and decides to have it for himself? Binya’s precious umbrella becomes the source of envy to him and he tries his utmost to get it from her. What follows is a story of greed, ignominy and kindness.\r\n\r\nThe book was adapted into a movie called The Blue Umbrella in 2005 and which was later awarded the National Film Award for Best Children’s Film.\r\n\r\nAbout Ruskin Bond\r\n\r\nRuskin Bond is a famous Indian writer, known especially for his children’s fiction. He has authored more than 300 stories, essays and novels.\r\n\r\nHis other novels are A Flight of Pigeons, Vagrants in the Valley, Angry River, The India I Love and Roads to Mussoorie. Some of his short story collections are A Town Called Dehra, The Night Train at Deoli and Tigers Forever.\r\n\r\nHe was born in 1934 in Kasauli and spent the major part of his childhood in Jamnagar, Shimla and Dehradun. He went to Bishop Cotton School in Shimla and graduated from there with numerous literary awards. Following this, he went to England and began work on his first novel, The Room on the Roof, which won him the John Llewellyn Rhys Prize. He returned to India and worked as a journalist for some time before moving to Mussoorie permanently. He received the Sahitya Akademi Award in 1992 and was awarded the prestigious Padma Shri in 1999. He has also authored two autobiographies namely Scenes from A Writer’s Life and Rain in the Mountains. A Flight of Pigeons and his short story Susanna’s Seven Husbands were also adapted into movies called Junoon and 7 Khoon Maaf respectively.', 'http://localhost/libgen/assets/uploads/images/books/65898f945d3b5.webp', 7, 4, 6, 1, '2023-12-25 15:20:04', '2023-12-25 15:20:04'),
(29, 'f353dd99-a330-11ee-a3ac-1ce422fe95f0', 'The Old Man And The Sea', 'Hemingway Ernest', 'About the Author\r\n\r\nErnest Miller Hemingway was a noted sportsman, journalist, and novelist. Hemingway wrote most of his works between the mid-1920s to the mid-1950s. He won the Nobel Prize for Literature in the year, 1954. Hemingway has written seven novels, six short story collections, and two works of non-fiction. Besides writing, Hemingway was a great sportsperson as well. This could be a reason for his frequent affiliation towards portraying tough characters in his works, including soldiers, hunters, and bullfighters. Some of the noted works of Hemingway include, Indian Camp, The Sun Also Rises, A Farewell to Arms, For Whom the Bell Tolls, The Old Man and the Sea.\r\n\r\nAbout the Book\r\n\r\nSome battles are meant to be fought and conquered on your own - like the one between Santiago and a large marlin. Ernest Hemingway in his book, The Old Man and the Sea, takes us on a trip to the shores with the central character Santiago, an aged fisherman. After being unable to catch fish for 84 days, he is seen as a “salao” (unlucky) by his village folk. On the eighty-fifth day, Santiago ventures into the Gulf Stream, north of Cuba, only to be attacked by a marlin. What follows is an adventurous episode between the old man and the sea. Will Santiago be able to tackle the fish? Will his streak of bad luck come to an end? A cosy evening with this book will lead you to the answers to these questions. This book by Ernest Hemingway won the Pulitzer Prize for Fiction in 1953.', 'http://localhost/libgen/assets/uploads/images/books/6589900117453.webp', 7, 4, 5, 1, '2023-12-25 15:21:53', '2023-12-25 15:21:53'),
(30, '4eb673b3-a331-11ee-a3ac-1ce422fe95f0', 'Adventures At School', 'Ruskin Bond', '‘In that last year at Prep school in Shimla, there were four of us who were close friends […] We called ourselves the “Four Feathers”, the feathers signifying that we were companions in adventure, comrades-in-arms and knights of the round table.’ School days play a major role in shaping who we are; our friends and teachers at school are the family we personally handpick. Not only does school teach us order, discipline and the importance of academic learning, it also gives us the priceless gifts of friendship, teamwork and goodness. Many of us, at times, may even struggle to remember the most obvious details of our everyday routines, however we, so fondly and effortlessly, hold onto our sometimes vivid, sometimes blurry memories of school life. Ruskin Bond’s Adventures at School presents mischievous adventurous as well as thrilling school experiences we can all identify with and relish. Some of these stories are sprinkled with innocent dreams, comical instances and heart-warming companionships, while others contain the author’s personal anecdotes as he recalls the carefree times of his own childhood. These tales push us towards appreciating our school days with a strong intensity, and make us reminisce them with bittersweet yearning and ringing heartstrings.', 'http://localhost/libgen/assets/uploads/images/books/6589909a69d57.webp', 5, 3, 6, 1, '2023-12-25 15:24:26', '2023-12-25 15:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `book_id` varchar(100) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `book_id`, `creation_date`, `modification_date`) VALUES
(48, 'de8026a2-a331-11ee-a3ac-1ce422fe95f0', '1ab911ec-a32c-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:29:28', '2023-12-25 15:29:28'),
(49, 'de8026a2-a331-11ee-a3ac-1ce422fe95f0', '60ddf41f-a330-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:29:39', '2023-12-25 15:29:39'),
(56, '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', '6de92ae3-a32f-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:43:25', '2023-12-25 15:43:25'),
(57, '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', '50823d2b-a32c-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:43:32', '2023-12-25 15:43:32'),
(58, '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', '09dfcf1c-a330-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:43:40', '2023-12-25 15:43:40'),
(59, 'de8026a2-a331-11ee-a3ac-1ce422fe95f0', 'be2723ba-a32f-11ee-a3ac-1ce422fe95f0', '2023-12-25 15:48:38', '2023-12-25 15:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `creation_date`, `modification_date`) VALUES
(5, 'Literature', '2023-12-25 14:37:48', '2023-12-25 14:37:48'),
(6, 'Philosophy', '2023-12-25 14:38:01', '2023-12-25 14:38:01'),
(7, 'Fiction', '2023-12-25 14:38:08', '2023-12-25 14:38:08'),
(8, 'Science', '2023-12-25 14:59:48', '2023-12-25 14:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` varchar(20) NOT NULL DEFAULT 'uuid()',
  `user_id` varchar(40) NOT NULL,
  `type` int(1) NOT NULL DEFAULT 1,
  `card` text NOT NULL,
  `amount` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `type`, `card`, `amount`, `creation_date`, `modification_date`) VALUES
('pay-658991b3816da', 'de8026a2-a331-11ee-a3ac-1ce422fe95f0', 1, '7854', 90, '2023-12-25 15:29:07', '2023-12-25 15:29:07'),
('pay-6589924e72f66', '2e75eec2-a332-11ee-a3ac-1ce422fe95f0', 1, '2569', 290, '2023-12-25 15:31:42', '2023-12-25 15:31:42'),
('pay-6589929b9d82d', '6e316c35-a332-11ee-a3ac-1ce422fe95f0', 1, '7456', 40, '2023-12-25 15:32:59', '2023-12-25 15:32:59'),
('pay-658992c055348', '6e316c35-a332-11ee-a3ac-1ce422fe95f0', 1, '1589', 90, '2023-12-25 15:33:36', '2023-12-25 15:33:36'),
('pay-6589938431f5d', 'e98ae939-a332-11ee-a3ac-1ce422fe95f0', 1, '1478', 160, '2023-12-25 15:36:52', '2023-12-25 15:36:52'),
('pay-658993dee940a', '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', 1, '7852', 10, '2023-12-25 15:38:22', '2023-12-25 15:38:22'),
('pay-658993fb896fe', '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', 1, '4258', 30, '2023-12-25 15:38:51', '2023-12-25 15:38:51'),
('pay-658994192d2d2', '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', 1, '1254', 120, '2023-12-25 15:39:21', '2023-12-25 15:39:21'),
('pay-658994f16c6df', '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', 2, '7425', 30, '2023-12-25 15:42:57', '2023-12-25 15:42:57'),
('pay-658995a73d164', 'e98ae939-a332-11ee-a3ac-1ce422fe95f0', 2, '7854', 25, '2023-12-25 15:45:59', '2023-12-25 15:45:59'),
('pay-658996a9bc24b', 'd0b9caaf-a332-11ee-a3ac-1ce422fe95f0', 1, '2569', 160, '2023-12-25 15:50:17', '2023-12-25 15:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `quantity`
--

CREATE TABLE `quantity` (
  `id` int(11) NOT NULL,
  `book_id` varchar(40) NOT NULL,
  `copies` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quantity`
--

INSERT INTO `quantity` (`id`, `book_id`, `copies`, `available`, `creation_date`, `modification_date`) VALUES
(17, '435aae16-a32b-11ee-a3ac-1ce422fe95f0', 50, 48, '2023-12-25 14:41:10', '2023-12-25 15:50:17'),
(18, '1ab911ec-a32c-11ee-a3ac-1ce422fe95f0', 15, 13, '2023-12-25 14:47:11', '2023-12-25 15:50:17'),
(19, '50823d2b-a32c-11ee-a3ac-1ce422fe95f0', 25, 24, '2023-12-25 14:48:41', '2023-12-25 15:50:17'),
(20, '8a0131f0-a32d-11ee-a3ac-1ce422fe95f0', 5, 4, '2023-12-25 14:57:27', '2023-12-25 15:36:52'),
(21, 'f69494dc-a32d-11ee-a3ac-1ce422fe95f0', 30, 30, '2023-12-25 15:00:30', '2023-12-25 15:45:59'),
(22, '83db4b7b-a32e-11ee-a3ac-1ce422fe95f0', 15, 15, '2023-12-25 15:04:27', '2023-12-25 15:46:39'),
(23, 'ca8c2049-a32e-11ee-a3ac-1ce422fe95f0', 30, 30, '2023-12-25 15:06:25', '2023-12-25 15:46:32'),
(24, '6de92ae3-a32f-11ee-a3ac-1ce422fe95f0', 14, 13, '2023-12-25 15:10:59', '2023-12-25 15:32:59'),
(25, 'be2723ba-a32f-11ee-a3ac-1ce422fe95f0', 18, 17, '2023-12-25 15:13:14', '2023-12-25 15:31:42'),
(26, '09dfcf1c-a330-11ee-a3ac-1ce422fe95f0', 15, 14, '2023-12-25 15:15:21', '2023-12-25 15:33:36'),
(27, '60ddf41f-a330-11ee-a3ac-1ce422fe95f0', 20, 20, '2023-12-25 15:17:47', '2023-12-25 15:17:47'),
(28, 'b284acca-a330-11ee-a3ac-1ce422fe95f0', 30, 29, '2023-12-25 15:20:04', '2023-12-25 15:39:21'),
(29, 'f353dd99-a330-11ee-a3ac-1ce422fe95f0', 20, 20, '2023-12-25 15:21:53', '2023-12-25 15:21:53'),
(30, '4eb673b3-a331-11ee-a3ac-1ce422fe95f0', 25, 25, '2023-12-25 15:24:26', '2023-12-25 15:42:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(40) NOT NULL DEFAULT uuid(),
  `name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `role` int(1) NOT NULL DEFAULT 1,
  `uniqueID` varchar(50) DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `address`, `password`, `image`, `active`, `role`, `uniqueID`, `creation_date`, `modification_date`) VALUES
(1, 'b6ff6038-9d88-11ee-a406-8a325a2dd4a0', 'Sajal Khawas', 'sajal@gmail.com', '#10, Street 33, Jujhar Nagar, SAS Nagar, Punjab - 140301, India', '$2y$10$0XvcR9Hp4B3i3WVz1Jkg/u4eXra9Ks69psBVlotVi1RAymOby1Kee', 'http://localhost/libgen/assets/uploads/images/users/65859a874d46a.jpg', 1, 3, NULL, '2023-12-15 12:15:10', '2023-12-25 14:24:37'),
(2, 'bc9590c5-a32a-11ee-a3ac-1ce422fe95f0', 'Karan Sharma', 'karan@gmail.com', 'Chandigarh, India', '$2y$10$7fLQ0mOcqT2nqOgRGTw6LOpvTdm2ae8SRwMhjWATx4U8Cgi4y42wm', NULL, 1, 3, NULL, '2023-12-25 14:37:24', '2023-12-25 15:26:05'),
(3, '86550e23-a331-11ee-a3ac-1ce422fe95f0', 'Pradeep Singh', 'pradeep@yahoo.com', 'Sector-34, Chandigarh, India', '$2y$10$SrSH9eYxBE5/lxFeylJdEuQWoqEX1jz00u3pnOcXIIX3tlDvnThRG', NULL, 1, 2, NULL, '2023-12-25 15:25:59', '2023-12-25 15:25:59'),
(4, 'a1cd3a47-a331-11ee-a3ac-1ce422fe95f0', 'Shagun', 'shagun@gmail.com', 'Mohali, Punjab, India', '$2y$10$1RELfV28SrB//C0R3TUGyuxVqcCVILrwFIcwEmFkkGKXq777ZPLC.', NULL, 1, 2, NULL, '2023-12-25 15:26:45', '2023-12-25 15:26:45'),
(5, 'de8026a2-a331-11ee-a3ac-1ce422fe95f0', 'Gourav Khawas', 'gouravkhawas@gmail.com', 'Maloya, Chandigarh, India', '$2y$10$qd5MSiswJp2cBJBDUtCRMOh1T9Hktpxl9JpVvISn2uNrwz1Jtpl8W', 'http://localhost/libgen/assets/uploads/images/users/6589918ba3bb0.jpg', 1, 1, NULL, '2023-12-25 15:28:27', '2023-12-25 15:28:27'),
(6, '2e75eec2-a332-11ee-a3ac-1ce422fe95f0', 'Neeraj', 'neeraj@gmail.com', 'Zirakpur, S.A.S. Nagar, Punjab, India', '$2y$10$iI50YsIDYDiVur5NiUHFEe0X4Am3VsF4u7F.3YLwxibPB1wAwjB52', NULL, 1, 1, NULL, '2023-12-25 15:30:41', '2023-12-25 15:30:41'),
(7, '6e316c35-a332-11ee-a3ac-1ce422fe95f0', 'Simran', 'simran@gmail.com', 'Kharar, SAS Nagar, Mohali, Punjab, India', '$2y$10$D0HvJkrkFFTfyLYSpo3LW.UOsk4cvNBlCPYcp6cKWfR6DayYMsdie', NULL, 1, 1, NULL, '2023-12-25 15:32:28', '2023-12-25 15:32:28'),
(8, 'd0b9caaf-a332-11ee-a3ac-1ce422fe95f0', 'Sukhpal', 'sukh@yahoo.com', 'Kharar, Punjab, India', '$2y$10$uRAP4858mMpFgML8YM3jzuXk5syXuS8PLZBCJLzgXu8Qowb53aeM.', NULL, 1, 1, NULL, '2023-12-25 15:35:14', '2023-12-25 15:35:14'),
(9, 'e98ae939-a332-11ee-a3ac-1ce422fe95f0', 'Aman', 'aman@yahoo.com', 'Dhanas, Chandigarh, India', '$2y$10$k/pbR9wwkeMwIvWgqOXpO.PdnKaUKWX5aFWev0Za.g.K0kJj4sp5a', NULL, 1, 1, NULL, '2023-12-25 15:35:55', '2023-12-25 15:35:55'),
(10, '2ab1dcbc-a333-11ee-a3ac-1ce422fe95f0', 'Param', 'param@gmail.com', 'Manimajra, Chandigarh', '$2y$10$Enflz32NlqzfuP2r9W8hruO6iIWlwpqKQWaIjqrFOQCzoQwMGgDR6', NULL, 1, 1, NULL, '2023-12-25 15:37:45', '2023-12-25 15:37:45'),
(11, '930cc96a-a333-11ee-a3ac-1ce422fe95f0', 'Lavish', 'lavish@yahoo.com', 'Murshidabad, Uttar Pradesh', '$2y$10$j668Bdzj5js/SpffQetSweiBxWhkNycEcF6zNrNlcKDe3qDx76Wsm', NULL, 1, 1, NULL, '2023-12-25 15:40:40', '2023-12-25 15:40:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`title`),
  ADD UNIQUE KEY `book_uuid` (`book_uuid`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`name`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `quantity`
--
ALTER TABLE `quantity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `uniqueID` (`uniqueID`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quantity`
--
ALTER TABLE `quantity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`type`) REFERENCES `payment_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quantity`
--
ALTER TABLE `quantity`
  ADD CONSTRAINT `quantity_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
