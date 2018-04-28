-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 28 avr. 2018 à 12:31
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test_rss`
--

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(2000) NOT NULL,
  `description` text NOT NULL,
  `date` varchar(1000) NOT NULL,
  `lien` varchar(10000) NOT NULL,
  `article` text NOT NULL,
  `categorie` varchar(1000) NOT NULL,
  PRIMARY KEY (`idMedia`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`idMedia`, `titre`, `description`, `date`, `lien`, `article`, `categorie`) VALUES
(147, 'DH.be - Actu', 'Le magistrat est porte-parole au Portalis. Un procÃ¨s sans accusÃ© : facile Ã  juger, direz-vous, surtout quand lâ€™auteur est poursuivi pour un quadruple meurtre. DÃ©trompez-vous, rÃ©pondra Denis Goeman. Le magistrat, qui incarne quotidiennement le ...', 'Sat, 28 Apr 2018 08:51:09 +0100', 'http://www.dhnet.be/actu/faits/le-magistrat-denis-goeman-dans-le-role-de-l-avocat-general-a-la-cour-d-assises-la-dh-l-a-suivi-en-coulisses-5ae337eccd704297e742740e', ' Faits divers  Le magistrat est porte-parole au Portalis.  Un procÃ¨s sans accusÃ© : facile Ã  juger, direz-vous, surtout quand lâ€™auteur est poursuivi pour un quadruple meurtre.  DÃ©trompez-vous, rÃ©pondra Denis Goeman. Le magistrat, qui incarne quotidiennement le visage du parquet de Bruxelles face aux camÃ©ras, a laissÃ© sa casquette de porte-parole au Portalis pour enfiler la tenue dâ€™avocat gÃ©nÃ©ral Ã  la cour dâ€™assises de Bruxelles.  (...) ', 'Faits divers'),
(146, 'Politique - www.lesoir.be', 'Les dirigeants se sont engagÃ©s se sont rencontrÃ©s et engagÃ©s Ã  Å“uvrer en faveur de la dÃ©nuclÃ©arisation.', 'Fri, 27 Apr 2018 20:06:02 +0200', 'http://www.lesoir.be/153757/article/2018-04-27/sommet-intercoreen-didier-reynders-espere-des-actions-concretes', '  	Sommet intercorÃ©en: Didier Reynders espÃ¨re des actions concrÃ¨tes 	   	   10   	Mis en ligne le 27/04/2018 Ã  20:06	     Par Belga   	Les dirigeants se sont engagÃ©s se sont rencontrÃ©s et engagÃ©s Ã  Å“uvrer en faveur de la dÃ©nuclÃ©arisation. 	   	Didier Reynders Â© Photo News	   	                     Le ministre belge des Affaires Ã©trangÃ¨res, Didier Reynders, a saluÃ© vendredi la rencontre Â«&nbsp;historique&nbsp;Â» entre les dirigeants des deux CorÃ©es, qui se sont engagÃ©s Ã  Å“uvrer en faveur de la dÃ©nuclÃ©arisation de la pÃ©ninsule.                                                   Â«                         &nbsp;Câ€™est une Ã©tape dans un processus que lâ€™on peut espÃ©rer positif dans les mois Ã  venir&nbsp;Â», a-t-il affirmÃ© en marge dâ€™une rÃ©union des ministres des Affaires Ã©trangÃ¨res des 29 pays de lâ€™Otan.                                                       Â«&nbsp;Jâ€™espÃ¨re que cela sera poursuivi comme annoncÃ© par une rencontre entre le prÃ©sident amÃ©ricain (Donald Trump) et le prÃ©sident nord-corÃ©en (Kim Jong Un)&nbsp;Â», a ajoutÃ© M. Reynders.                                                                                                                                   Du concret                                                       Il a toutefois insistÃ© sur la nÃ©cessitÃ© de mettre en place et Â«&nbsp;surtout en Å“uvre&nbsp;Â» un plan dâ€™actions concrÃ¨tes.                                  Une rencontre Ã  tel niveau entre les deux dirigeants corÃ©ens peut Ãªtre considÃ©rÃ©e comme Â«&nbsp;historique                     &nbsp;Â», avec le dÃ©marrage dâ€™un processus, a encore soulignÃ© le chef de la diplomatie belge.                                                                                                              Les dirigeants des deux CorÃ©es se sont engagÃ©s vendredi, lors dâ€™une rencontre dans le village de Panmunjom, sur la ligne de dÃ©marcation dans la Zone dÃ©militarisÃ©e (DMZ) qui sÃ©pare les deux pays, Ã  Å“uvrer en faveur de la dÃ©nuclÃ©arisation en promettant quâ€™il nâ€™y aurait plus de guerre sur la pÃ©ninsule.                                                        	 		Lire aussi 		Sommet historique intercorÃ©en: Â«Les Etats-Unis vont devoir montrer patte blanche 	                  	   	Sur le mÃªme sujet CorÃ©e: les photos historiques de la rencontre entre Kim Jong Un et Moon Jae-in Les retrouvailles entre les deux CorÃ©es: Â«Nous sommes sur une ligne de dÃ©partÂ»   	  ', 'Les retrouvailles entre les deux CorÃ©es: Â«Nous sommes sur une ligne de dÃ©partÂ»');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
