-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. M&auml;rz 2010 um 17:46
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
-- Tabellenstruktur f&uuml;r Tabelle `p_kunde`
--

DROP TABLE IF EXISTS `p_kunde`;
CREATE TABLE IF NOT EXISTS `p_kunde` (
  `kid` int(11) NOT NULL AUTO_INCREMENT,
  `kname` varchar(255) NOT NULL DEFAULT '',
  `text_de` text NOT NULL,
  `text_en` text NOT NULL,
  `ziel_de` text NOT NULL,
  `ziel_en` text NOT NULL,
  `anz_de` varchar(255) NOT NULL,
  `anz_en` varchar(255) NOT NULL,
  `sub_de` varchar(100) NOT NULL,
  `sub_en` varchar(100) NOT NULL,
  `etat_de` text NOT NULL,
  `etat_en` text NOT NULL,
  `edit` int(1) NOT NULL DEFAULT '1',
  `lid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
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

INSERT INTO `p_kunde` (`kid`, `kname`, `text_de`, `text_en`, `ziel_de`, `ziel_en`, `anz_de`, `anz_en`, `sub_de`, `sub_en`, `etat_de`, `etat_en`, `edit`, `lid`, `bid`, `casestudy_de`, `casestudy_en`, `factsheet`, `nlink`, `sichtbar`) VALUES
(1, 'K&ouml;nig+Neurath', 'F&uuml;r K&ouml;nig+Neurath, einem der f&uuml;hrenden B&uuml;rom&ouml;belhersteller in Europa, verantwortete Peakom die Konzeption und Umsetzung des Gesamtetats f&uuml;r die Marketing- und Vertriebskommunikation. Schwerpunkt bildete die strategische Neupositionierung des Unternehmens als B2B-Marke.', 'K&ouml;nig+Neurath, one of Europe’s leading office furniture manufacturers entrusts Peakom with the conception and implementation of its entire budget for marketing and sales communication. The focus is centred on the strategic repositioning of the company as a B2B brand. ', '', '', '', '', 'B&uuml;rom&ouml;bel', 'Office furniture', 'Channel Marketing', 'Channel marketing', 1, 3, 4, 'k+n factsheet.pdf', '', '', 'cid=72&p2=5&p3=69&p4=72', 1),
(2, 'P&I', 'F&uuml;r P&I, einen der europ&auml;ischen Marktf&uuml;hrer  f&uuml;r integrierte personalwirtschaftliche Softwarel&ouml;sungen, hat  Peakom ein neues Marken- und Kommunikationskonzept entwickelt. Die differenzierten  Anforderungen der Kunden und ein un&uuml;bersichtliches Wettbewerbsumfeld  f&uuml;r HR-Software mussten dabei ber&uuml;cksichtigt werden. ', 'For P&I, one of Europe’s leading suppliers for integrated HR-software-solutions, Peakom has developed a new brand and communication concept. Therefore the sophisticated demands of the customers and the complex competitive environment for HR-software had  to be considered carefully. ', '', '', '', '', 'Software', 'Software', 'Unternehmens- und Vertriebskommunikation', 'Corporate- and distribution-communication', 1, 3, 3, 'casestudy_p&i.pdf', 'case_study_p&i_e.pdf', '', 'cid=73&p2=5&p3=69&p4=73', 1),
(3, 'Allianz', '', '', '', '', '', '', 'Allfinanz', 'Allfinanz', 'Fokussierung neuer Vertriebspotenziale', 'Development of new distribution potentials', 1, 3, 1, '', '', '', '', 1),
(4, 'aurelis', 'Entwicklung Namen und CD mit Key Visuals, Umsetzung z.B. in Website und Veranstaltungen, Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Projektkommunikation', ' was wir f&uuml;r sie tun: Englisch', 'Ergebniss: Deutsch', 'Ergebniss: Englisch', 'aurelis', 'aurelis', 'Immobilien Management', 'Real estate management', 'Unternehmens- und Projektkommunikation, projektbezogene Markenkommunikation', 'Corporate- and project-communication, projekt-branding', 1, 1, 2, '', '', '', '', 1),
(5, 'Bouwfonds MAB Development', 'F&uuml;r den niederl&auml;ndischen Immobilienprojektentwickler Bouwfonds MAB Development betreut Peakom die Unternehmenskommunikation und Projektkommunikation f&uuml;r „FrankfurtHochVier“ (Investitionsvolumen: 800 Millionen Euro).', 'Peakom handles the corporate- and project communication of „FrankfurtHochVier“ for the Dutch real estate developer Bouwfonds MAB Development.', '', '', '', '', 'Immobilienentwickler  und -betreiber', 'Real estate developer and -operator', 'Projektbezogene Markenkommunikation', 'Total budget', 1, 1, 2, 'case_study_bouwfonds_neu.pdf', 'case_study_bouwfonds_e.pdf', '', 'cid=70&p2=5&p3=69&p4=70', 1),
(6, 'Bundesagentur f&uuml;r Arbeit', '', '', '', '', '', '', 'Staatliche Institution', 'State institution', 'Neukonzeption des Medienportfolios und der Berufsinformationszentren', 'Conceptual scheme for media portfolio and job information centers', 1, 3, 1, '', '', '', '', 1),
(7, 'Deutsche Annington Immobilien', '', '', '', '', '', '', 'Wohnungswirtschaft', 'Housing industry', 'Aufbau einer neuen Unternehmenskultur', 'Constitution of a new corporate culture', 1, 2, 2, '', '', '', '', 1),
(8, 'DB Consult', '', '', '', '', '', '', 'M&A-Berater Deutsche Bank', 'M&A-consulting, Deutsche Bank Group', 'Erweiterung Markenprofil', 'Extension of brand profile', 1, 1, 1, '', '', '', '', 1),
(9, 'Deutsche Bahn', '', '', '', '', '', '', 'Personenreiseverkehr', 'German Rail', 'Restrukturierung Organisation', 'Organization restructuring ', 1, 2, 4, '', '', '', '', 1),
(10, 'Deutsche Immobilien Chancen (DIC)', 'Entwicklung Namen und CD mit Key Visual, Umsetzung z.B. in Website, Brosch&uuml;ren und Werbung f&uuml;r die Vertriebs- und Projektkommunikation', '', '', '', '', '', 'Immobilieninvestor', 'Real estate investor', 'Projektbezogene Markenkommunikation', 'Project-related brand communication', 1, 1, 2, 'maintor factsheet.pdf', '', '', '', 1),
(11, 'dit', 'F&uuml;r die Investmentfondsgesellschaft der Allianz-Dresdner  Bank Gruppe hat Peakom die gesamte B2B-Kommunikation aufgebaut. Die Zielgruppe „Anlageberater“ erfordert aufgrund ihrer strategischen Marktbedeutung und ihrer spezifischen Anforderungen ein eigenst&auml;ndiges Kommunikationskonzept: argumentativ aber involvierend. ', 'For the investment funds company of the Allianz-Dresdner Bank group Peakom has build-up the entire B2B-communication. „Financial advisors“ as  the target group required a special communication concept due to their  strategic significance in the market and their specific needs: argumentative  but involving. ', '', '', '', '', 'Investmentfonds, Allianz Gruppe', 'Investment funds, Allianz group', 'Ausbau Vertriebsorganisation', 'Distribution organization upgrade', 1, 3, 1, 'casestudy_dit.pdf', 'case_study_dit_e.pdf', '', 'cid=71&p2=5&p3=69&p4=71', 0),
(12, 'GFT Technologie', '', '', '', '', '', '', 'Software Systemintegration', 'Software system-integration', 'Organisationsver&auml;nderung', 'Organizational change ', 1, 2, 3, '', '', '', '', 1),
(13, 'Hitachi Europe', '', '', '', '', '', '', 'Digital Signage Media', 'Digital signage media', 'Relaunch Vertriebsstruktur', 'Re-launch distribution structure', 1, 3, 3, '', '', '', '', 1),
(14, 'KPMG', '', '', '', '', '', '', 'Financial Advisory Services', 'Financial Advisory Services', 'Fokussierung der Gesch&auml;ftsfelder', 'Optimization of business segments', 1, 2, 1, '', '', '', '', 1),
(15, 'Lilly Deutschland', '', '', '', '', '', '', 'Pharmaunternehmen', 'Pharmaceutical company', 'Relaunch Vertrieb Critical Care', 'Re-launch distribution Critical Care', 1, 3, 5, '', '', '', '', 1),
(17, 'OFB', '', '', '', '', '', '', 'Immobilientwickler Helaba', 'Real estate developer of Hessische Landesbank', 'Projektbezogenes Vertriebsmarketing', 'Project-based distribution marketing', 1, 3, 2, '', '', '', '', 1),
(18, 'Statkraft', '', '', '', '', '', '', 'Energieerzeuger', 'Power producer', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 1, 4, '', '', '', '', 1),
(19, 'Stiegelmeyer', 'F&uuml;r den f&uuml;hrenden Systemanbieter im Pflege- und Klinikbereich hat Peakom ein neues Marken- und Kommunkationskonzept entwickelt. Den differenzierten Kunden-Anforderungen und dem Kostendruck in Kliniken, lieferte das WellCare-Konzept eine klare Antwort. ', 'For the leading supplier of care and health systems Peakom developed a new brand and communication system. The WellCare-Concept provides a clear response to sophisticated customer demands and the cost pressure of clinics. ', '', '', '', '', 'Pflegeausstattungen', 'Clinic-  and care equipment manufacturer', 'CD und Gesamtetat', 'CD and total budget', 1, 1, 5, '', '', '', '', 1),
(20, 'T-Mobile', 'Im Rahmen einer Restrukturierung der Organisation - zur  Wertsteigerung des gesamten Portfolios von &uuml;ber 20 Millionen Kunden  in Deutschland - wurde Peakom beauftragt, die Change Communication zu  planen und zu steuern. Basis war die interne Kommunikation im verantwortlichen  Unternehmensbereich f&uuml;r den Kundenservice.', 'Peakom was commissioned to develop and control the Change Communication within the framework of the proposed organization restructuring – targeting  a value increase of the entire portfolio of over 20 million customers  in Germany. Key issue was the internal communication in the customer service sector. ', '', '', '', '', 'Telekommunikation', 'Telecommunication', 'Fokussierung Kundenservices', 'Optimization customer services', 1, 2, 3, '', '', '', '', 1),
(21, 'Xerox Deutschland', 'F&uuml;r den Premiumanbieter von Kopier – und Druckmaschinen entwickelte und realisierte Peakom ein Gesamtkonzept f&uuml;r das Channel Marketing im Bereich B&uuml;roger&auml;te.  Die hochwertige „Solid-Ink“-Technologie als Beleg f&uuml;r  die Innovationskraft der Marke Xerox wurde ausschließlich &uuml;ber  den Vertrieb kommuniziert. ', 'Peakom has developed and realized an entire Channel Marketing campaign for the premium provider of office equipment. The high quality of the „solid-ink“-technology as a proof of innovation-power  behind the brand Xerox has been communicated exclusively via the distribution  channel.', '', '', '', '', 'Kopier- und Druckmaschinen', 'Copiers and printers', 'Vertriebsoptimierung', 'Distribution optimization', 1, 3, 3, '', '', '', '', 1),
(22, 'BKW FMB Energie', '', '', '', '', '', '', 'unabh&auml;ngiger Energieerzeuger und -versorger', 'independent energyproducer', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 2, 4, '', '', '', '', 1),
(23, 'Morgan Stanley Properties', 'Peakom gibt dem CityTower in Offenbach ein neues Gesicht und macht ihn zu einer starken Immobilienmarke im Rhein-Main-Gebiet. Nach der Logo- und Claim-Entwicklung setzt Peakom das neue Markenbild in der aktiven Vermarktung um. Die Aktivit&auml;ten umfassen Mailings, die Vermarktungsbrosch&uuml;re, Veranstaltungen bis hin zur Onlinekommunikation mit Website und Suchmaschinenmarketing.', 'Peakom gives the CityTower in Offenbach a new identity and turns it into a strong real estate brand of the Rhine-Main region. After developing the new logo and slogan, Peakom implemets the new design into the marketing mix. Activities range from direct mails, the brochure, events to online communications including website and search engine marketing.', '', '', '', '', 'Immobilien Management', 'Real estate management', 'Projektbezogenes Vertriebsmarketing', 'Project-based channel marketing', 1, 3, 2, '', '', '', '', 1),
(25, 'AXA Asset Managers Deutschland', '', '', '', '', '', '', 'Investmentprodukte', 'Investment products', 'Vertriebsmarketing', 'Channel marketing', 1, 3, 1, '', '', '', '', 1),
(26, 'Bocholt Power GmbH & Co.KG', '', '', '', '', '', '', 'Energie', 'Energy', 'Projektkommunikation, PR', 'project-communication, PR', 1, 1, 4, '', '', '', '', 1),
(27, 'BVK Bayerische Versorgungskammer', '', '', '', '', '', '', 'Versicherung', 'Insurance', 'Entwicklung Corporate Design, Unternehmens- und Vertriebskommunikation', 'Development corporate design, corporate communications and channel marketing', 1, 1, 1, '', '', '', '', 1),
(28, 'Union Investment Real Estate', 'Entwicklung eines ganzheitlichen Markenauftritts f&uuml;r das Landmark EMPORIO (ehemaliges Unileverhaus) in Hamburg inklusive Name, Logo und Corporate Design. Umsetzung in Website und Brosch&uuml;re.\r\n', ' ', '', '', '', '', 'Immobilien', 'Retail', 'Project Branding', 'Project Branding', 1, 1, 2, 'emporio factsheet.pdf', '', '', '', 1),
(30, 'Arcadis Deutschland', 'Entwicklung eines ganzheitlichen Marketingkonzeptes. Marken-Relaunch im Rahmen einer Akquisition, mit Abbildung neuer Marktsegmente auf Corporate-Ebene. Weiterentwicklung des Corporate Design und Umsetzung von Unternehmens-Foldern.\r\n', 'Development of an integrated marketing concept. Brand re-launch in the context of an acquisition, reproducing new marketing segments on corporate level. Enhancement of the corporate design and transformation of corporate folders.', '', '', '', '', 'Arcadis ', 'Arcadis ', 'Unternehmens- und Projektkommunikation, Marken-Relaunch, Markenkommunikation', 'Corporate- and project-communication, brand relaunch', 1, 1, 2, '', '', '', '', 1),
(31, 'Arcadis Deutschland', '', '', '', '', '', '', 'Immobilien', 'Retail', 'Unternehmens- und Projektkommunikation, Marken-Relaunch, Markenkommunikation', 'Corporate- and project-communication, brand relaunch', 1, 3, 2, '', '', '', '', 1),
(32, 'Deutsche Bank', 'Entwicklung, Umsetzung und fortlaufende Erg&auml;nzung Multimedia-Tool der "Greentowers" als Kern der gesamten Projektkommunikation, inkl. "Ausspielung" im Internet (<a href="http://www.greentowers.de" target="_blank" style="text-decoration:underline;">www.greentowers.de</a>) und auf Veranstaltungen', '', '', '', '', '', 'Kommunikation ', 'Communication ', 'Projektkommunikation', 'project-communication', 1, 1, 2, '', '', '', '', 1),
(33, 'RAG Montan Immobilien', 'Entwicklung CD mit Key Visual, Umsetzung z.B. in Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Vertriebskommunikation von einzelnen Projekten', '', '', '', '', '', 'Immobilien / Fl&auml;chenentwicklung', 'Real estate management / land development', 'Unternehmens- und Projektkommunikation', 'Corporate- and project-communication', 1, 1, 2, '', '', '', '', 1),
(34, 'Bilfinger Berger Hochbau', 'Entwicklung CD mit Key Visual "MindMap", Umsetzung z.B. in Website, Brosch&uuml;ren und Werbung f&uuml;r die Unternehmens- und Vertriebskommunikation', '', '', '', '', '', 'Immobilien', 'Real Estate Management', 'Vertriebskommunikation', 'Distribution-communication', 1, 1, 2, '', '', '', '', 1),
(35, 'Joh. Gutenberg-Universit&auml;t Mainz', '', '', '', '', '', '', '&ouml;ffentliche Verwaltung', 'Public administration', 'Logo- und Corporate Design-Entwicklung', 'Design and development of logo and corporate design', 1, 1, 1, '', '', '', '', 1),
(36, 'Pfrimmer Nutricia', '', '', '', '', '', '', 'Medizinische Ern&auml;hrung', 'Medical nutrition', 'Interne Kommunikation, Change Communication', 'In-house communication, Change Communication', 1, 2, 5, '', '', '', '', 1),
(37, 'COMPASS Private Pflegeberatung', 'F&uuml;r die COMPASS Private Pflegeberatung gestaltete Peakom den gesamten Markenauftritt inklusive Name und Claim. Auch die Vertriebskommunikation, wie zum Beispiel die Entwicklung eines Flyers f&uuml;r die Versicherten, etc. wurde von Peakom &uuml;bernommen.', 'Peakom designed the brand identity for COMPASS Private Pflegeberatung, including name and claim. The sales communication, e.g. a flyer for the insured, was also developed by Peakom.', '', '', '', '', 'Gesundheitswirtschaft', 'Health care management', 'Corporate Branding, Vertriebskommunikation', 'Corporate branding, sales communication', 1, 1, 5, 'compass factsheet.pdf', '', '', '', 1),
(38, 'Universit&auml;tsmedizin der Joh. Gutenberg-Universit&auml;t Mainz', 'Peakom entwickelt f&uuml;r die Universit&auml;tsmedizin der Johannes Gutenberg-Universit&auml;t Mainz im Rahmen ihrer Neuausrichtung das Corporate Design inklusive Logo. Folgende Markenattribute weist die neu entwickelte Marke auf: glaubw&uuml;rdig, modern, menschlich und hochwertig. Der Fokus liegt auf der st&auml;rkeren Vernetzung der drei Leistungsebenen Forschung, Lehre und Krankenversorgung. Ein weiterer Schwerpunkt bildet die Integration der verschiedenen Kliniken als Submarken. Diese Anforderungen wurden bei der Ausarbeitung des Corporate Design beispielhaft in Brosch&uuml;ren, Flyern, Anzeigen und Plakaten ber&uuml;cksichtigt.', '', '', '', '', '', 'Krankenversorgung, Forschung und Lehre', '', 'Corporate Branding', 'Corporate branding', 1, 1, 5, '', '', '', '', 1),
(39, 'Screening Zentrum Mainz', 'Peakom unterst&uuml;tzt das Screening Zentrum Mainz bei der Konzeption und Umsetzung verschiedener Maßnahmen zur Etablierung des bundesweiten Programms zum Mammographie-Screening. Die Aktivit&auml;ten umfassen neben der Gestaltung von Basismedien u.a. auch die Realisierung einer Testimonialkampagne.', 'Peakom supports the Screening Zentrum of Mainz in establishing the nationwide program for mammography screening. Besides the design of different media, activities also include the realization of a testmonial campaign.', '', '', '', '', 'Medizinische Einrichtung', 'medical facility', 'Corporate Branding', 'Corporate Branding', 1, 1, 5, '', '', '', '', 1),
(40, 'GEWOBA', '', '', '', '', '', '', 'Immobilienwirtschaft', 'Retail estate industry', 'F&uuml;hrungskr&auml;ftetagung, Change Communication', 'Leadership conference, Change Communication', 1, 2, 2, '', '', '', '', 1),
(41, 'Chartis Europe', '', '', '', '', '', '', 'Versicherung', 'Insurance', 'Marken-Relaunch, Marketing-Kommunikation', 'Brand relaunch, marketing communications', 1, 1, 1, '', '', '', '', 1),
(42, 'Chartis Europe', '', '', '', '', '', '', 'Versicherung', 'Insurance', 'Marken-Relaunch, Marketing-Kommunikation', 'Brand relaunch, marketing communications', 1, 3, 1, '', '', '', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
