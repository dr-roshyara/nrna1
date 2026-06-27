Principles and requirements for a secure e-voting system
Author links open overlay panelDimitris A Gritzalis a b

Show more

Add to Mendeley

Share

Cite
https://doi.org/10.1016/S0167-4048(02)01014-3
Get rights and content
Abstract
Electronic voting (e-voting) is considered a means to further enhance and strengthen the democratic processes in modern information societies. E-voting should first comply with the existing legal and regulatory framework. Moreover, e-voting should be technically implemented in such a way that ensures adequate user requirements. As a result, the aim of this paper is twofold. Firstly, to identify the set of generic constitutional requirements, which should be met when designing an e-voting system for general elections. This set will lead to the specific (design) principles of a legally acceptable e-voting system. Second, to identify, using the Rational Unified Process, the requirements of an adequately secure e-voting system. These requirements stem from the design principles identified previously. The paper concludes that an e-voting capability should, for the time being, be considered only as a complementary means to the traditional election processes. This is mainly due to the digital divide, to the inherent distrust in the e-voting procedure, as well as to the inadequacy of the existing technological means to meet certain requirements.
Introduction
The emerging Information Society has enabled people in the developed countries to perform several of their activities in a direct, electronically automated and efficient way. To keep up with the need to provide citizens with the ability to benefit from services over networks, as well as to reduce the cost and bureaucracy of public administration, governments are striving to transfer an increasing number of their activities to the new medium.
E-voting can be an efficient and cost effective way for conducting a voting procedure and for attracting specific groups of people (e.g. young or disabled electors) to participate [1]. The term e-voting (electronic voting) is used hereby to denote a voting process, which enables voters to cast a secure and secret ballot over a network. In this paper, e-voting refers to general elections and/or referenda, at state and/or local level, with binding effects.
Many public authorities are, in general, concerned with the compliance of electronic voting systems with the existing legal (i.e. constitutional) framework. The first aim of the paper is to discuss whether an e-voting scheme could meet the legal requirements, as these are laid down in the modern information societies. The paper discusses how an e-vote process should be designed and implemented, in order to comply with the democratic election principles and rights, as well as to the other human rights, which constitute the cornerstone of the international legal civilization. Along these lines, the requirements of an electronic voting system are considered as the design principles, which are essential to comply with, in order to conform to the legislation framework, which is governing general elections [2]. Although technology moves at a pace faster than the legal system does, technological evolution should be pursued as a means to improve human life, as opposed to an end by itself. In this respect, the technological developments — and in particular those affecting fundamental principles — should be carefully reviewed with an eye towards ensuring their contribution to the improvement of the quality of the citizen life.
The second aim of this paper is to discuss confidence upon technology. Information system developers face e-vote systems with an eye towards ensuring their adequate level of security [3], [4], [5], [6], [7]. In recent literature, a distinction is often made between different types of e-voting systems requirements [8]. In literature, requirements are usually identified as legal, technical and user-oriented — the latter in the form of conditions the system should meet (e.g. “the system shall allow online-voting from home”). Other authors select a specific election procedure (e.g. the paper absentee ballot process [9]), deriving requirements for electronic voting systems based solely on this procedure. Although such approaches may produce acceptable e-voting systems in given contexts, they have not yet led to the specification of a complete system. This paper focuses on the elicitation of the legal and functional requirements of an e-voting system, through a User Requirements Specification suitable for providing information system designers with the essential information for designing a valid and complete system. A milestone, towards this end, is the development of a generic e-voting model by depicting the principles and practices to be followed during an election procedure.
The paper is structured as follows: section 2 refers to the main issues regarding e-voting for general (public) elections and summarizes the generic constitutional requirements and the corresponding design principles such an election process should meet. Section 3 analyzes further a voting system design principles. Section 4 presents briefly the methodology, which will be used to identify and describe the user requirements of an e-voting system, while Section 5 overviews briefly the traditional voting model. Section 6 provides the reader with the functional security requirements of an internet-based e-voting system, while Section 7 describes the non-functional security requirements. Section 8 argues why an e-voting system should be considered only as complementary to traditional systems. Finally, Section 9 concludes the paper.
Access through your organization
Check access to the full text by signing in through your organization.

Section snippets
E-voting main issues
A fundamental challenge of electronic democracy is to improve and develop representative democracy and strengthen processes aiming at the empowerment of citizens [10]. The new civilization, brought about by the Information Society, should comply with the principles and values of democracy. The introduction of an e-voting system should conform to this rule, since voting is one of the functions “e-citizens” may wish to see performed online. In this respect, a phenomenon, which should be taken
Generality
Universal suffrage is a generic principle for democratic elections, requesting that every eligible voter can participate in the election process, and nobody can be excluded or discriminated. The consequences deriving from this principle are the following:
1.
Every voter has the right to participate in an election process.
2.
The ability to participate in an election process (eligibility) must be founded on and be controllable by the law.
3.
Voting possibilities and technologies should be accessible by
Methodology used
In this section, the generic constitutional requirements (and the corresponding design principles) will be facilitated as a basis for eliciting the functional user requirements. This elicitation will be based on the Rational Unified Process [22], [23]. The Rational Unified Process is the synthesis of various software development processes; one of its most important characteristics is that it is use-case driven. Use cases were introduced as a requirements capturing method. Each use case refers
The traditional voting model
The voting process can be generally reviewed in the context of general elections. However, there are other situations where voting plays a central role (e.g. internal elections [e.g. trade unions elections], decision-making [e.g. referenda], polls of indicative or advisory nature, etc.). These procedures are conducted in a way similar to general elections, although usually governed by different legal framework.
Nevertheless, one can argue that the general election process is a superset of the
E-voting user and functional security-focused requirements
The general election model described in the previous section provides the essential basis for an e-voting system requirements elicitation. In line with the business use cases of the general elections model, a number of system use cases have been identified. The business use cases, regarding a general e-voting model appear in Figure 2. A detailed description of all e-voting business use cases is described in the sequel, followed by the corresponding user and functional requirements, in
E-voting non-functional security requirements
In addition to the user and functional requirements expressed through the system use cases, the system will exhibit a number of non-functional requirements. Non-functional requirements can either be specific to a use case or they may pertain to the system as a whole. These requirements have been grouped into the following categories:
Security: Aim to support the main security properties, both in application and system level; they also provide for non-repudiation, anonymity and source
Suggested use of an e-voting system
We argue that e-voting systems should be viewed, for the time being, only as a supplement to — and not a replacement of — the existing paper-based voting systems. We base our suggestion mainly on the following:
1.
The digital divide, i.e. the lack of equal access opportunity to the Internet and to the ICT infrastructure means. Offering new means and possibilities of participation, based on ICT, could in such a case lead to the opposite effect, namely the exclusion of “ICT illiterate” voters from
Conclusions
Information and Communication Technologies are powerful instruments in the hands of politicians and legislators, who have the duty to actively promote the democratic process and encourage citizen participation. Technology could help overcome the crisis of confidence, that representative democracy is experiencing nowadays. The right to vote is a part of the democratic process, which remains deeply embedded in the modern constitutions. Moreover, it is considered to be one of the primary
Acknowledgments
2This work has been supported in part by the European Commission, IST/e-vote project (“An Internet-based electronic voting system”). The author wishes to thank C. Lambrinoudakis, L. Mitrou, as well as the anonymous referees, for their valuable comments and suggestions. References on pp. 552–4.
Dimitris Gritzalis
Dimitris Gritzalis holds Ph.D. (Information Systems Security), M.Sc. (Computer Science) and B.Sc. (Mathematics) degrees. He is an Assistant Professor of Computer and Network Security, with the Dept. of Informatics of the Athens University of Economics and Business (Greece), where he leads the Infosec Research Group. He is, also, an Associate Data Protection Commissioner of Greece.
References (29)
Internet Policy Institute, Report of the National Workshop on Internet Voting: Issues and Research Agenda, March 2001...
The Swedish Government, Internet Voting — Final Report from the Election Technique Commission, 2000...
Cramer R., Franklin M., Schoenmakers B., Yung M., “Multi-authority secret ballot elections with linear work”, Lecture...
Schoenmakers B., “A simple publicly verifiable secret sharing scheme and its application to electronic voting”, in...
Buttler R., et al., “A national-scale authentication infrastructure”, Computer, Vol. 33, no. 2, pp. 60–65, February...
Hoffman L., Cranor L., “Internet voting for public officials”, in Com. of the ACM, Vol. 44, no. 1, pp. 69–71, January...
Jones B., A report on the feasibility of Internet voting, Internet Voting Task Force, State of California, January...
CyberVote (IST-1999-20338 project), Report on electronic democracy projects, legal issues of Internet voting and users...
United Sates, State of California, A Report on the Feasibility of Internet Voting, January 2000...
European Commission, IST 2000 Programme, The Information Society for all, Final Report, Brussels...

View more references
Cited by (132)
Peace engineering: The contribution of blockchain systems to the e-voting process
2021, Technological Forecasting and Social Change

Show abstract
Securing e-Government and e-Voting with an open cloud computing architecture
2011, Government Information Quarterly
Citation Excerpt :
An intruder must be expected to use any available means of penetration and shall attack a system at its most vulnerable point. The client's personal computer is identified as the weakest point in an e-Voting environment (Jefferson et al., 2004; Gritzalis, 2002; Cranor, 2003). Voters' home computers are most likely to be less defended than corporate ones, as they often run outdated virus protection systems, misconfigured firewalls, unpatched operating systems, and contain numerous applications from various vendors, making these machines especially susceptible to malicious attacks.


Show abstract
Blockchain-Based E-Voting Systems: A Technology Review
2024, Electronics Switzerland
The Application of the Blockchain Technology in Voting Systems
2022, ACM Computing Surveys
E-Voting with Blockchain: An E-Voting Protocol with Decentralisation and Voter Privacy
2018, Proceedings IEEE 2018 International Congress on Cybermatics 2018 IEEE Conferences on Internet of Things Green Computing and Communications Cyber Physical and Social Computing Smart Data Blockchain Computer and Information Technology Ithings Greencom Cpscom Smartdata Blockchain CIT 2018
A review of E-voting: the past, present and future
2016, Annales Des Telecommunications Annals of Telecommunications
View all citing articles on Scopus