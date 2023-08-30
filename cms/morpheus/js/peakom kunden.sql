-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. M&auml;rz 2010 um 12:13
-- Server Version: 5.1.37
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `peakom`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur f&uuml;r Tabelle `p_branche`
--

DROP TABLE IF EXISTS `p_branche`;
CREATE TABLE IF NOT EXISTS `p_branche` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `bname_de` varchar(255) NOT NULL,
  `bname_en` varchar(255) NOT NULL,
  PRIMARY KEY (`bid`),
  UNIQUE KEY `name` (`bname_de`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten f&uuml;r Tabelle `p_branche`
--

INSERT INTO `p_branche` (`bid`, `bname_de`, `bname_en`) VALUES
(1, 'Finanzen und andere Dienstleistungen', 'Financial and other Services'),
(2, 'Immobilien / Bau', 'Real Estate Industry'),
(3, 'IT / Telekommunikation', 'IT / Telecommunication'),
(4, 'Energie / Industrie', 'Energy / Industry'),
(5, 'Pharma / Gesundheit', 'Pharmacy / Health');

-- --------------------------------------------------------

--
-- Tabellenstruktur f&uuml;r Tabelle `p_images`
--

DROP TABLE IF EXISTS `p_images`;
CREATE TABLE IF NOT EXISTS `p_images` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `morp_cms_image` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `kid` int(11) NOT NULL,
  `bereich` int(11) NOT NULL,
  `reihenfolge` int(11) NOT NULL,
  `startbild` int(1) NOT NULL,
  `text_de` text COLLATE latin1_general_ci NOT NULL,
  `text_en` text COLLATE latin1_general_ci NOT NULL,
  `headl_de` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `headl_en` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=19 ;

--
-- Daten f&uuml;r Tabelle `p_images`
--

INSERT INTO `p_images` (`iid`, `morp_cms_image`, `kid`, `bereich`, `reihenfolge`, `startbild`, `text_de`, `text_en`, `headl_de`, `headl_en`) VALUES
(1, '001CRES7_titel.jpg', 30, 2, 1, 1, 'eo if&uuml;io \r\n *9eu 09Z &uuml;)F(ES&uuml;)(D HF&uuml;)SHFPIDJVIUS\r\nDORJ H&uuml;POITDJ* HJSDOIKX\r\nwe &auml;aojgósp', '', '1ladh oiah iho', ''),
(2, '001Exit_innen.jpg', 30, 2, 4, 0, '', '', '', ''),
(3, '001CRES7_innen.jpg', 30, 2, 2, 0, '', '', '', ''),
(4, '001Exit_Titel.jpg', 30, 2, 3, 1, 'text 2', '', '', ''),
(5, '001FacilityManagement_Innen.jpg', 30, 2, 6, 0, '', '', '', ''),
(6, '001FacilityManagementTitel.jpg', 30, 2, 5, 1, 'text 3', '', '', ''),
(7, '001Geotechnik_innen.jpg', 30, 2, 8, 0, '', '', '', ''),
(8, '001Geotechnik_Titel.jpg', 30, 2, 7, 1, 'text 4', '', '', ''),
(9, '001TDD_innen.jpg', 30, 2, 10, 0, 'text 5', '', '', ''),
(10, '001TDD_Titel.jpg', 30, 2, 9, 1, '', '', 'titel 4', ''),
(11, '001BB_Anzeige.jpg', 30, 1, 1, 1, '', '', '', ''),
(12, '001BB_AZ_Nachhaltig_231x325_RZ.jpg', 30, 1, 2, 1, '', '', '', ''),
(13, '001BB_AZ_Wirtschaftlich_231x325_RZ.jpg', 30, 1, 3, 1, '', '', '', ''),
(14, '001BB_Innen.jpg', 30, 1, 4, 0, '', '', '', ''),
(15, '001BB_Innen2.jpg', 30, 1, 5, 0, '', '', '', ''),
(16, '001BB_Innen3.jpg', 30, 1, 6, 0, '', '', '', ''),
(17, '001BB_Innen4.jpg', 30, 1, 7, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur f&uuml;r Tabelle `p_kunde`
--

DROP TABLE IF EXISTS `p_kunde`;
CREATE TABLE IF NOT EXISTS `p_kunde` (
  `kid` int(11) NOT NULL AUTO_INCREMENT,
  `kname` varchar(255) NOT NULL DEFAULT '',
  `text_de` text NOT NULL,
  `text_en` text NOT NULL,
  `sub_de` varchar(100) NOT NULL,
  `sub_en` varchar(100) NOT NULL,
  `etat_de` text NOT NULL,
  `etat_en` text NOT NULL,
  `edit` int(1) NOT NULL DEFAULT '1',
  `lid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `img1` varchar(50) NOT NULL,
  `img2` varchar(50) NOT NULL,
  `img3` varchar(50) NOT NULL,
  `img4` varchar(50) NOT NULL,
  `img5` varchar(50) NOT NULL,
  `img6` varchar(50) NOT NULL,
  `img7` varchar(50) NOT NULL,
  `img8` varchar(50) NOT NULL,
  `img9` varchar(50) NOT NULL,
  `img10` varchar(50) NOT NULL,
  `img11` varchar(50) NOT NULL,
  `img12` varchar(50) NOT NULL,
  `img13` varchar(50) NOT NULL,
  `img14` varchar(50) NOT NULL,
  `img15` varchar(50) NOT NULL,
  `img16` varchar(50) NOT NULL,
  `img17` varchar(50) NOT NULL,
  `img18` varchar(50) NOT NULL,
  `img19` varchar(50) NOT NULL,
  `img20` varchar(50) NOT NULL,
  `casestudy_de` varchar(100) NOT NULL,
  `casestudy_en` varchar(100) NOT NULL,
  `factsheet` varchar(100) NOT NULL,
  `nlink` varchar(150) NOT NULL,
  `sichtbar` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Daten f&uuml;r Tabelle `p_kunde`
--

INSERT INTO `p_kunde` (`kid`, `kname`, `text_de`, `text_en`, `sub_de`, `sub_en`, `etat_de`, `etat_en`, `edit`, `lid`, `bid`, `img1`, `img2`, `img3`, `img4`, `img5`, `img6`, `img7`, `img8`, `img9`, `img10`, `img11`, `img12`, `img13`, `img14`, `img15`, `img16`, `img17`, `img18`, `img19`, `img20`, `casestudy_de`, `casestudy_en`, `factsheet`, `nlink`, `sichtbar`) VALUES
(1, 'K&ouml;nig+Neurath', 'F&uuml;r K&ouml;nig+Neurath, einem der f&uuml;hrenden B&uuml;rom&ouml;belhersteller in Europa, verantwortete Peakom die Konzeption und Umsetzung des Gesamtetats f&uuml;r die Marketing- und Vertriebskommunikation. Schwerpunkt bildete die strategische Neupositionierung des Unternehmens als B2B-Marke.', 'K&ouml;nig+Neurath, one of Europe’s leading office furniture manufacturers entrusts Peakom with the conception and implementation of its entire budget for marketing and sales communication. The focus is centred on the strategic repositioning of the company as a B2B brand. ', 'B&uuml;rom&ouml;bel', 'Office furniture', 'Channel Marketing', 'Channel marketing', 1, 3, 4, 'k+nanzeige1.jpg', 'k+nanzeige2.jpg', 'k+nerstkontaktfolder1.jpg', 'k+nerstkontaktfolder2.jpg', 'k+nerstkontaktfolder2_1.jpg', 'k+nerstkontaktfolder3.jpg', 'k+nsalesmagazin.jpg', 'micro1web.jpg', 'micro2web.jpg', 'micro3web.jpg', 'micro4web.jpg', 'micro5web.jpg', 'micro6web.jpg', 'kn_markenbuch.jpg', 'kn-bilder1.jpg', 'kn-bilder2.jpg', 'diva_titel.jpg', 'kinetatitel.jpg', 'kn_el_allgemein.jpg', 'factbook_titel.jpg', 'k+n factsheet.pdf', '', '', 'cid=72&p2=5&p3=69&p4=72', 1),
(2, 'P&I', 'F&uuml;r P&I, einen der europ&auml;ischen Marktf&uuml;hrer  f&uuml;r integrierte personalwirtschaftliche Softwarel&ouml;sungen, hat  Peakom ein neues Marken- und Kommunikationskonzept entwickelt. Die differenzierten  Anforderungen der Kunden und ein un&uuml;bersichtliches Wettbewerbsumfeld  f&uuml;r HR-Software mussten dabei ber&uuml;cksichtigt werden. ', 'For P&I, one of Europe’s leading suppliers for integrated HR-software-solutions, Peakom has developed a new brand and communication concept. Therefore the sophisticated demands of the customers and the complex competitive environment for HR-software had  to be considered carefully. ', 'Software', 'Software', 'Unternehmens- und Vertriebskommunikation', 'Corporate- and distribution-communication', 1, 3, 3, 'anzeige1.jpg', 'anzeige2.jpg', 'anzeige3.jpg', 'anzeige4.jpg', 'pin_januar07.jpg', 'pin_maerz06.jpg', 'pin_juli06.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', 'casestudy_p&i.pdf', 'case_study_p&i_e.pdf', '', 'cid=73&p2=5&p3=69&p4=73', 1),
(3, 'Allianz', '', '', 'Allfinanz', 'Allfinanz', 'Fokussierung neuer Vertriebspotenziale', 'Development of new distribution potentials', 1, 3, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(4, 'aurelis', 'Entwicklung Namen und CD mit Key Visuals, Umsetzung z.B. in Website und Veranstaltungen, Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Projektkommunikation', ' ', 'Immobilien Management', 'Real estate management', 'Unternehmens- und Projektkommunikation, projektbezogene Markenkommunikation', 'Corporate- and project-communication, projekt-branding', 1, 1, 2, 'aur_helenenhoefe-1.jpg', 'aur_helenenhoefe-4.jpg', 'aur_helenenhoefe_web.jpg', 'aur_boulevard-mitte_1009-1.jpg', 'aur_boulevard-mitte-2.jpg', 'aur_boulevard_mitte_web.jpg', 'aur_boulevard-west_1009-1.jpg', 'aur_boulevard-west-3.jpg', 'aur_boulevardwest_web.jpg', 'aur_stadtgaerten.jpg', 'aur_stadtgaerten-4.jpg', 'aur_stadtgaerten_web.jpg', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(5, 'Bouwfonds MAB Development', 'F&uuml;r den niederl&auml;ndischen Immobilienprojektentwickler Bouwfonds MAB Development betreut Peakom die Unternehmenskommunikation und Projektkommunikation f&uuml;r „FrankfurtHochVier“ (Investitionsvolumen: 800 Millionen Euro).', 'Peakom handles the corporate- and project communication of „FrankfurtHochVier“ for the Dutch real estate developer Bouwfonds MAB Development.', 'Immobilienentwickler  und -betreiber', 'Real estate developer and -operator', 'Projektbezogene Markenkommunikation', 'Total budget', 1, 1, 2, 'insite3_titel.jpg', 'insite4_titel.jpg', 'insite4_s08_09.jpg', 'insite6_titel.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'case_study_bouwfonds_neu.pdf', 'case_study_bouwfonds_e.pdf', '', 'cid=70&p2=5&p3=69&p4=70', 1),
(6, 'Bundesagentur f&uuml;r Arbeit', '', '', 'Staatliche Institution', 'State institution', 'Neukonzeption des Medienportfolios und der Berufsinformationszentren', 'Conceptual scheme for media portfolio and job information centers', 1, 3, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(7, 'Deutsche Annington Immobilien', '', '', 'Wohnungswirtschaft', 'Housing industry', 'Aufbau einer neuen Unternehmenskultur', 'Constitution of a new corporate culture', 1, 2, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(8, 'DB Consult', '', '', 'M&A-Berater Deutsche Bank', 'M&A-consulting, Deutsche Bank Group', 'Erweiterung Markenprofil', 'Extension of brand profile', 1, 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(9, 'Deutsche Bahn', '', '', 'Personenreiseverkehr', 'German Rail', 'Restrukturierung Organisation', 'Organization restructuring ', 1, 2, 4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(10, 'Deutsche Immobilien Chancen (DIC)', 'Entwicklung Namen und CD mit Key Visual, Umsetzung z.B. in Website, Brosch&uuml;ren und Werbung f&uuml;r die Vertriebs- und Projektkommunikation', '', 'Immobilieninvestor', 'Real estate investor', 'Projektbezogene Markenkommunikation', 'Project-related brand communication', 1, 1, 2, 'winx_broschuere_rz_1-1.jpg', 'winx_broschuere_rz_1-2.jpg', 'winx_broschuere_rz_1-3.jpg', 'winx_broschuere_rz_1-10.jpg', 'dicbauteilabroschuere.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'maintor factsheet.pdf', '', '', '', 1),
(11, 'dit', 'F&uuml;r die Investmentfondsgesellschaft der Allianz-Dresdner  Bank Gruppe hat Peakom die gesamte B2B-Kommunikation aufgebaut. Die Zielgruppe „Anlageberater“ erfordert aufgrund ihrer strategischen Marktbedeutung und ihrer spezifischen Anforderungen ein eigenst&auml;ndiges Kommunikationskonzept: argumentativ aber involvierend. ', 'For the investment funds company of the Allianz-Dresdner Bank group Peakom has build-up the entire B2B-communication. „Financial advisors“ as  the target group required a special communication concept due to their  strategic significance in the market and their specific needs: argumentative  but involving. ', 'Investmentfonds, Allianz Gruppe', 'Investment funds, Allianz group', 'Ausbau Vertriebsorganisation', 'Distribution organization upgrade', 1, 3, 1, 'beileger2_1.jpg', 'beileger1_1.jpg', 'beileger4.jpg', 'fondstrophytitel.jpg', 'onlineufo.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'casestudy_dit.pdf', 'case_study_dit_e.pdf', '', 'cid=71&p2=5&p3=69&p4=71', 0),
(12, 'GFT Technologie', '', '', 'Software Systemintegration', 'Software system-integration', 'Organisationsver&auml;nderung', 'Organizational change ', 1, 2, 3, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(13, 'Hitachi Europe', '', '', 'Digital Signage Media', 'Digital signage media', 'Relaunch Vertriebsstruktur', 'Re-launch distribution structure', 1, 3, 3, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(14, 'KPMG', '', '', 'Financial Advisory Services', 'Financial Advisory Services', 'Fokussierung der Gesch&auml;ftsfelder', 'Optimization of business segments', 1, 2, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(15, 'Lilly Deutschland', '', '', 'Pharmaunternehmen', 'Pharmaceutical company', 'Relaunch Vertrieb Critical Care', 'Re-launch distribution Critical Care', 1, 3, 5, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(17, 'OFB', '', '', 'Immobilientwickler Helaba', 'Real estate developer of Hessische Landesbank', 'Projektbezogenes Vertriebsmarketing', 'Project-based distribution marketing', 1, 3, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(18, 'Statkraft', '', '', 'Energieerzeuger', 'Power producer', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 1, 4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(19, 'Stiegelmeyer', 'F&uuml;r den f&uuml;hrenden Systemanbieter im Pflege- und Klinikbereich hat Peakom ein neues Marken- und Kommunkationskonzept entwickelt. Den differenzierten Kunden-Anforderungen und dem Kostendruck in Kliniken, lieferte das WellCare-Konzept eine klare Antwort. ', 'For the leading supplier of care and health systems Peakom developed a new brand and communication system. The WellCare-Concept provides a clear response to sophisticated customer demands and the cost pressure of clinics. ', 'Pflegeausstattungen', 'Clinic-  and care equipment manufacturer', 'CD und Gesamtetat', 'CD and total budget', 1, 1, 5, 'anz_nadine.jpg', 'care_broschuere.jpg', 'stiegelmeyer2.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(20, 'T-Mobile', 'Im Rahmen einer Restrukturierung der Organisation - zur  Wertsteigerung des gesamten Portfolios von &uuml;ber 20 Millionen Kunden  in Deutschland - wurde Peakom beauftragt, die Change Communication zu  planen und zu steuern. Basis war die interne Kommunikation im verantwortlichen  Unternehmensbereich f&uuml;r den Kundenservice.', 'Peakom was commissioned to develop and control the Change Communication within the framework of the proposed organization restructuring – targeting  a value increase of the entire portfolio of over 20 million customers  in Germany. Key issue was the internal communication in the customer service sector. ', 'Telekommunikation', 'Telecommunication', 'Fokussierung Kundenservices', 'Optimization customer services', 1, 2, 3, 'tmobile3.jpg', 't-mobile1.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(21, 'Xerox Deutschland', 'F&uuml;r den Premiumanbieter von Kopier – und Druckmaschinen entwickelte und realisierte Peakom ein Gesamtkonzept f&uuml;r das Channel Marketing im Bereich B&uuml;roger&auml;te.  Die hochwertige „Solid-Ink“-Technologie als Beleg f&uuml;r  die Innovationskraft der Marke Xerox wurde ausschließlich &uuml;ber  den Vertrieb kommuniziert. ', 'Peakom has developed and realized an entire Channel Marketing campaign for the premium provider of office equipment. The high quality of the „solid-ink“-technology as a proof of innovation-power  behind the brand Xerox has been communicated exclusively via the distribution  channel.', 'Kopier- und Druckmaschinen', 'Copiers and printers', 'Vertriebsoptimierung', 'Distribution optimization', 1, 3, 3, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(22, 'BKW FMB Energie', '', '', 'unabh&auml;ngiger Energieerzeuger und -versorger', 'independent energyproducer', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 2, 4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(23, 'Morgan Stanley Properties', 'Peakom gibt dem CityTower in Offenbach ein neues Gesicht und macht ihn zu einer starken Immobilienmarke im Rhein-Main-Gebiet. Nach der Logo- und Claim-Entwicklung setzt Peakom das neue Markenbild in der aktiven Vermarktung um. Die Aktivit&auml;ten umfassen Mailings, die Vermarktungsbrosch&uuml;re, Veranstaltungen bis hin zur Onlinekommunikation mit Website und Suchmaschinenmarketing.', 'Peakom gives the CityTower in Offenbach a new identity and turns it into a strong real estate brand of the Rhine-Main region. After developing the new logo and slogan, Peakom implemets the new design into the marketing mix. Activities range from direct mails, the brochure, events to online communications including website and search engine marketing.', 'Immobilien Management', 'Real estate management', 'Projektbezogenes Vertriebsmarketing', 'Project-based channel marketing', 1, 3, 2, '001_minifolder.jpg', 'citytower_imagebroschuere_kl-2.jpg', 'citytower_imagebroschuere_kl-5.jpg', 'citytower_webseite_1.jpg', 'citytower_webseite_2.jpg', 'citytower_webseite_3.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(25, 'AXA Asset Managers Deutschland', '', '', 'Investmentprodukte', 'Investment products', 'Vertriebsmarketing', 'Channel marketing', 1, 3, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(26, 'Bocholt Power GmbH & Co.KG', '', '', 'Energie', 'Energy', 'Projektkommunikation, PR', 'project-communication, PR', 1, 1, 4, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(27, 'BVK Bayerische Versorgungskammer', '', '', 'Versicherung', 'Insurance', 'Entwicklung Corporate Design, Unternehmens- und Vertriebskommunikation', 'Development corporate design, corporate communications and channel marketing', 1, 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(28, 'Union Investment Real Estate', 'Entwicklung eines ganzheitlichen Markenauftritts f&uuml;r das Landmark EMPORIO (ehemaliges Unileverhaus) in Hamburg inklusive Name, Logo und Corporate Design. Umsetzung in Website und Brosch&uuml;re.\r\n', ' ', 'Immobilien', 'Retail', 'Project Branding', 'Project Branding', 1, 1, 2, 'emporio.jpg', 'altarfalz > rz_layout 1-4.jpg', 'altarfalz > rz_layout 1-6.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'emporio factsheet.pdf', '', '', '', 1),
(30, 'Arcadis Deutschland', 'Entwicklung eines ganzheitlichen Marketingkonzeptes. Marken-Relaunch im Rahmen einer Akquisition, mit Abbildung neuer Marktsegmente auf Corporate-Ebene. Weiterentwicklung des Corporate Design und Umsetzung von Unternehmens-Foldern.\r\n', 'Development of an integrated marketing concept. Brand re-launch in the context of an acquisition, reproducing new marketing segments on corporate level. Enhancement of the corporate design and transformation of corporate folders.', 'Arcadis ', 'Arcadis ', 'Unternehmens- und Projektkommunikation, Marken-Relaunch, Markenkommunikation', 'Corporate- and project-communication, brand relaunch', 1, 1, 2, '', '', '', '', 'facility management.jpg', 'arcadis_facility management folder_rz-2.jpg', 'arcadis_titel_siteexit.jpg', 'pk-09-008_arcadis_exit_folder_isouncoated-2.jpg', 'arcadis_titel_geotechnik.jpg', 'pk-09-012_arcadis_geotechnik_folder_uc-2.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(31, 'Arcadis Deutschland', '', '', 'Immobilien', 'Retail', 'Unternehmens- und Projektkommunikation, Marken-Relaunch, Markenkommunikation', 'Corporate- and project-communication, brand relaunch', 1, 3, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(32, 'Deutsche Bank', 'Entwicklung, Umsetzung und fortlaufende Erg&auml;nzung Multimedia-Tool der "Greentowers" als Kern der gesamten Projektkommunikation, inkl. "Ausspielung" im Internet (<a href="http://www.greentowers.de" target="_blank" style="text-decoration:underline;">www.greentowers.de</a>) und auf Veranstaltungen', '', 'Kommunikation ', 'Communication ', 'Projektkommunikation', 'project-communication', 1, 1, 2, '080911_broschuere_projectblue.jpg', 'db multitool1.jpg', 'db multitool2.jpg', '', 'db multitool3.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(33, 'RAG Montan Immobilien', 'Entwicklung CD mit Key Visual, Umsetzung z.B. in Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Vertriebskommunikation von einzelnen Projekten', '', 'Immobilien / Fl&auml;chenentwicklung', 'Real estate management / land development', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 1, 2, 'rag_anzeige1.jpg', 'rag_anzeige2.jpg', 'ragmi_folder1.jpg', 'ragmi_folder2.jpg', 'ragmi_folder3.jpg', 'ragmi_folder4.jpg', 'ragmi_folder5.jpg', 'rag_titel.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(34, 'Bilfinger Berger Hochbau', 'Entwicklung CD mit Key Visual "MindMap", Umsetzung z.B. in Website, Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Vertriebskommunikation', '', 'Immobilien', 'Real Estate Management', 'Vertriebskommunikation', 'Distribution-communication', 1, 1, 2, 'bb_az_08_15.jpg', 'bb_az_ganzheitlich.jpg', 'bb_az_nachhaltig.jpg', 'bb_az_wirtschaftlich.jpg', 'bb_titel_ppp.jpg', 'bb_titel_pflegeimmobilien.jpg', 'bb_titel_nachhaltigkeit.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(35, 'Joh. Gutenberg-Universit&auml;t Mainz', '', '', '&ouml;ffentliche Verwaltung', 'Public administration', 'Logo- und Corporate Design-Entwicklung', 'Design and development of logo and corporate design', 1, 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(36, 'Pfrimmer Nutricia', '', '', 'Medizinische Ern&auml;hrung', 'Medical nutrition', 'Interne Kommunikation, Change Communication', 'In-house communication, Change Communication', 1, 2, 5, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(37, 'COMPASS Private Pflegeberatung', 'F&uuml;r die COMPASS Private Pflegeberatung gestaltete Peakom den gesamten Markenauftritt inklusive Name und Claim. Auch die Vertriebskommunikation, wie zum Beispiel die Entwicklung eines Flyers f&uuml;r die Versicherten, etc. wurde von Peakom &uuml;bernommen.', 'Peakom designed the brand identity for COMPASS Private Pflegeberatung, including name and claim. The sales communication, e.g. a flyer for the insured, was also developed by Peakom.', 'Gesundheitswirtschaft', 'Health care management', 'Corporate Branding, Vertriebskommunikation', 'Corporate branding, sales communication', 1, 1, 5, 'compass_aussen.jpg', 'compass anzeige_layout 1.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'compass factsheet.pdf', '', '', '', 1),
(38, 'Universit&auml;tsmedizin der Joh. Gutenberg-Universit&auml;t Mainz', 'Peakom entwickelt f&uuml;r die Universit&auml;tsmedizin der Johannes Gutenberg-Universit&auml;t Mainz im Rahmen ihrer Neuausrichtung das Corporate Design inklusive Logo. Folgende Markenattribute weist die neu entwickelte Marke auf: glaubw&uuml;rdig, modern, menschlich und hochwertig. Der Fokus liegt auf der st&auml;rkeren Vernetzung der drei Leistungsebenen Forschung, Lehre und Krankenversorgung. Ein weiterer Schwerpunkt bildet die Integration der verschiedenen Kliniken als Submarken. Diese Anforderungen wurden bei der Ausarbeitung des Corporate Design beispielhaft in Brosch&uuml;ren, Flyern, Anzeigen und Plakaten ber&uuml;cksichtigt.', '', 'Krankenversorgung, Forschung und Lehre', '', 'Corporate Branding', 'Corporate branding', 1, 1, 5, 'unimainzanzeige.jpg', 'unimainzcd_manual.jpg', 'uni_cd-manual_0.jpg', 'uni_cd-manual_01.jpg', 'uni_cd-manual_1.jpg', 'uni_cd-manual2.jpg', 'uni_cd-manual_3.jpg', 'uni_cd-manual_4.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(39, 'Screening Zentrum Mainz', 'Peakom unterst&uuml;tzt das Screening Zentrum Mainz bei der Konzeption und Umsetzung verschiedener Maßnahmen zur Etablierung des bundesweiten Programms zum Mammographie-Screening. Die Aktivit&auml;ten umfassen neben der Gestaltung von Basismedien u.a. auch die Realisierung einer Testimonialkampagne.', 'Peakom supports the Screening Zentrum of Mainz in establishing the nationwide program for mammography screening. Besides the design of different media, activities also include the realization of a testmonial campaign.', 'Medizinische Einrichtung', 'medical facility', 'Corporate Branding', 'Corporate Branding', 1, 1, 5, 'screeningz_webseite_1.jpg', 'screeningz_webseite_2.jpg', 'screeningz_webseite_3.jpg', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(40, 'GEWOBA', '', '', 'Immobilienwirtschaft', 'Retail estate industry', 'F&uuml;hrungskr&auml;ftetagung, Change Communication', 'Leadership conference, Change Communication', 1, 2, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(41, 'Chartis Europe', '', '', 'Versicherung', 'Insurance', 'Marken-Relaunch, Marketing-Kommunikation', 'Brand relaunch, marketing communications', 1, 1, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1),
(42, 'Chartis Europe', '', '', 'Versicherung', 'Insurance', 'Marken-Relaunch, Marketing-Kommunikation', 'Brand relaunch, marketing communications', 1, 3, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur f&uuml;r Tabelle `p_loesung`
--

DROP TABLE IF EXISTS `p_loesung`;
CREATE TABLE IF NOT EXISTS `p_loesung` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `lname_de` varchar(255) NOT NULL,
  `lname_en` varchar(255) NOT NULL,
  PRIMARY KEY (`lid`),
  UNIQUE KEY `name` (`lname_de`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten f&uuml;r Tabelle `p_loesung`
--

INSERT INTO `p_loesung` (`lid`, `lname_de`, `lname_en`) VALUES
(1, 'Corporate Branding', 'Corporate Branding'),
(2, 'Change Communication', 'Change Communication'),
(3, 'Channel Marketing', 'Channel Marketing');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
