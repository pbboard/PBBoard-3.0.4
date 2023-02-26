<?php

@set_time_limit(0);

define('PBB_ROOT', dirname(dirname(__FILE__))."/");
define("INSTALL_ROOT", dirname(__FILE__)."/");
define("TIME_NOW", time());
define("IN_PBB", 1);
define("IN_INSTALL", 1);
define("INSTALL", 1);


if(function_exists('date_default_timezone_set') && !ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

require_once PBB_ROOT.'includes/class_error.php';
$error_handler = new errorHandler();

require_once INSTALL_ROOT."resources/functions.php";

require_once INSTALL_ROOT.'resources/class_core.php';
$PBBoard = new PBBoard;

require_once PBB_ROOT.'includes/class_xml.php';
// Load DB interface
require_once PBB_ROOT."includes/db_base.php";
// General systems
require_once PBB_ROOT."includes/functions.class.php";

// Include the installation resources
if(isset($_COOKIE['pbb_insall_lang']) == 'ar')
{
require_once INSTALL_ROOT.'resources/output_ar.php';
$output = new installerOutput;
require_once INSTALL_ROOT.'resources/class_language.php';
$lang = new MyLanguage();
$lang->set_path(PBB_ROOT.'install/resources/');
$lang->load($_COOKIE['pbb_insall_lang']);
$output->loadlang = $_COOKIE['pbb_insall_lang'];
$mysql_db_inserts_file = 'mysql_db_inserts.php';
}
elseif(isset($_COOKIE['pbb_insall_lang']) == 'en')
{
require_once INSTALL_ROOT.'resources/output.php';
$output = new installerOutput;
require_once INSTALL_ROOT.'resources/class_language.php';
$lang = new MyLanguage();
$lang->set_path(PBB_ROOT.'install/resources/');
$lang->load($_COOKIE['pbb_insall_lang']);
$lang->load('en');
$mysql_db_inserts_file = 'mysql_db_inserts_en.php';
}
else
{
require_once INSTALL_ROOT.'resources/output_ar.php';
$output = new installerOutput;
require_once INSTALL_ROOT.'resources/class_language.php';
$lang = new MyLanguage();
$lang->set_path(PBB_ROOT.'install/resources/');
$lang->load('ar');
$output->loadlang = 'ar';
$mysql_db_inserts_file = 'mysql_db_inserts.php';

}
$input = array();

$doneheader = 0;
$version_code = 1806;
$script = "index.php";

// Perform a check if PBBoard is already installed or not
$installed = false;
if(file_exists(PBB_ROOT."/includes/config.php"))
{
	require PBB_ROOT."/includes/config.php";
	if(isset($config) && is_array($config))
	{
		$installed = true;
		if(isset($config['Misc']['admincpdir']))
		{
			$admin_dir = $config['Misc']['admincpdir'];
		}
		else if(isset($config['Misc']['admincpdir']))
		{
			$admin_dir = $config['Misc']['admincpdir'];
		}
	}
}
$dboptions = array();


if(function_exists('mysqli_connect'))
{
	$dboptions['mysqli'] = array(
		'class' => 'DB_MySQLi',
		'title' => 'MySQL Improved',
		'short_title' => 'MySQLi',
		'structure_file' => 'mysql_db_tables.php',
		'population_file' => $mysql_db_inserts_file
	);
}

if(function_exists('mysql_connect'))
{
	$dboptions['mysql'] = array(
		'class' => 'DB_MySQL',
		'title' => 'MySQL',
		'short_title' => 'MySQL',
		'structure_file' => 'mysql_db_tables.php',
		'population_file' => $mysql_db_inserts_file
	);
}


$output->title = $lang->title;

	function get_languag($c_lang)
	{
	   global $output, $lang, $PBBoard;

		if($c_lang)
		 {
		  setcookie('pbb_insall_lang', $c_lang, time() + (2880 * 2), "/");
		  echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=index.php\">\n";
		 }
	}

	if(!empty($_POST['selectlang']))
	 {
	 	get_languag($_POST['selectlang']);

	 }
   if(!isset($_COOKIE['pbb_insall_lang']))
	{
	 $output->print_header($lang->choose_languag, "errormsg", 0);
	 echo $lang->choose_a_language;
     $output->print_footer();
     $lang->load($_COOKIE['pbb_insall_lang']);
     exit();
	}

	if(file_exists('lock') && $PBBoard->dev_mode != true)
	{
       $output->print_error($lang->locked);
	}
	elseif($installed == true && empty($PBBoard->input['action']))
	{
		$output->print_header($lang->already_installed, "errormsg", 0);
		echo $lang->sprintf($lang->PBBoard_already_installed, $PBBoard->version);
		$output->print_footer();
	}
	else
	{
      $output->steps = array(
		'intro' => $lang->welcome,
		'license' => $lang->license_agreement,
		'requirements_check' => $lang->req_check,
		'database_info' => $lang->db_config,
		'create_tables' => $lang->table_creation,
		'populate_tables' => $lang->data_insertion,
		'templates' => $lang->theme_install,
		'languages' => $lang->lang_install,
		'configuration' => $lang->board_config,
		'adminuser' => $lang->admin_user,
		'final' => $lang->finish_setup,
		);


	    switch($PBBoard->get_input('action'))
		{
			case 'license':
				license_agreement();
				break;
			case 'requirements_check':
				requirements_check();
				break;
			case 'database_info':
				database_info();
				break;
			case 'create_tables':
				create_tables();
				break;
			case 'populate_tables':
				populate_tables();
				break;
			case 'templates':
				insert_templates();
				break;
			case 'languages':
				language();
				break;
			case 'configuration':
				configure();
				break;
			case 'adminuser':
				create_admin_user();
				break;
			case 'final':
			install_done();
			break;
		default:
			$PBBoard->input['action'] = 'intro';
			intro();
			break;

		}

   }

/**
 * Welcome page
 */
function intro()
{
	global $output, $PBBoard, $lang;

	$output->print_header($lang->welcome, 'welcome');
	if(strpos(strtolower(get_current_location('', '', true)), '/upload/') !== false)
	{
		echo $lang->sprintf($lang->PBBoard_incorrect_folder);
	}
	echo $lang->sprintf($lang->welcome_step, $PBBoard->version);
	$output->print_footer('license');
}



/**
 * Show the license agreement
 */
function license_agreement()
{
	global $output, $lang, $PBBoard;

	ob_start();
	$output->print_header($lang->license_agreement, 'license');
	ob_end_flush();

	$license = <<<EOF
<pre>
                   GNU LESSER GENERAL PUBLIC LICENSE
                       Version 3, 29 June 2007

 Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.


  This version of the GNU Lesser General Public License incorporates
the terms and conditions of version 3 of the GNU General Public
License, supplemented by the additional permissions listed below.

  0. Additional Definitions.

  As used herein, "this License" refers to version 3 of the GNU Lesser
General Public License, and the "GNU GPL" refers to version 3 of the GNU
General Public License.

  "The Library" refers to a covered work governed by this License,
other than an Application or a Combined Work as defined below.

  An "Application" is any work that makes use of an interface provided
by the Library, but which is not otherwise based on the Library.
Defining a subclass of a class defined by the Library is deemed a mode
of using an interface provided by the Library.

  A "Combined Work" is a work produced by combining or linking an
Application with the Library.  The particular version of the Library
with which the Combined Work was made is also called the "Linked
Version".

  The "Minimal Corresponding Source" for a Combined Work means the
Corresponding Source for the Combined Work, excluding any source code
for portions of the Combined Work that, considered in isolation, are
based on the Application, and not on the Linked Version.

  The "Corresponding Application Code" for a Combined Work means the
object code and/or source code for the Application, including any data
and utility programs needed for reproducing the Combined Work from the
Application, but excluding the System Libraries of the Combined Work.

  1. Exception to Section 3 of the GNU GPL.

  You may convey a covered work under sections 3 and 4 of this License
without being bound by section 3 of the GNU GPL.

  2. Conveying Modified Versions.

  If you modify a copy of the Library, and, in your modifications, a
facility refers to a function or data to be supplied by an Application
that uses the facility (other than as an argument passed when the
facility is invoked), then you may convey a copy of the modified
version:

   a) under this License, provided that you make a good faith effort to
   ensure that, in the event an Application does not supply the
   function or data, the facility still operates, and performs
   whatever part of its purpose remains meaningful, or

   b) under the GNU GPL, with none of the additional permissions of
   this License applicable to that copy.

  3. Object Code Incorporating Material from Library Header Files.

  The object code form of an Application may incorporate material from
a header file that is part of the Library.  You may convey such object
code under terms of your choice, provided that, if the incorporated
material is not limited to numerical parameters, data structure
layouts and accessors, or small macros, inline functions and templates
(ten or fewer lines in length), you do both of the following:

   a) Give prominent notice with each copy of the object code that the
   Library is used in it and that the Library and its use are
   covered by this License.

   b) Accompany the object code with a copy of the GNU GPL and this license
   document.

  4. Combined Works.

  You may convey a Combined Work under terms of your choice that,
taken together, effectively do not restrict modification of the
portions of the Library contained in the Combined Work and reverse
engineering for debugging such modifications, if you also do each of
the following:

   a) Give prominent notice with each copy of the Combined Work that
   the Library is used in it and that the Library and its use are
   covered by this License.

   b) Accompany the Combined Work with a copy of the GNU GPL and this license
   document.

   c) For a Combined Work that displays copyright notices during
   execution, include the copyright notice for the Library among
   these notices, as well as a reference directing the user to the
   copies of the GNU GPL and this license document.

   d) Do one of the following:

       0) Convey the Minimal Corresponding Source under the terms of this
       License, and the Corresponding Application Code in a form
       suitable for, and under terms that permit, the user to
       recombine or relink the Application with a modified version of
       the Linked Version to produce a modified Combined Work, in the
       manner specified by section 6 of the GNU GPL for conveying
       Corresponding Source.

       1) Use a suitable shared library mechanism for linking with the
       Library.  A suitable mechanism is one that (a) uses at run time
       a copy of the Library already present on the user's computer
       system, and (b) will operate properly with a modified version
       of the Library that is interface-compatible with the Linked
       Version.

   e) Provide Installation Information, but only if you would otherwise
   be required to provide such information under section 6 of the
   GNU GPL, and only to the extent that such information is
   necessary to install and execute a modified version of the
   Combined Work produced by recombining or relinking the
   Application with a modified version of the Linked Version. (If
   you use option 4d0, the Installation Information must accompany
   the Minimal Corresponding Source and Corresponding Application
   Code. If you use option 4d1, you must provide the Installation
   Information in the manner specified by section 6 of the GNU GPL
   for conveying Corresponding Source.)

  5. Combined Libraries.

  You may place library facilities that are a work based on the
Library side by side in a single library together with other library
facilities that are not Applications and are not covered by this
License, and convey such a combined library under terms of your
choice, if you do both of the following:

   a) Accompany the combined library with a copy of the same work based
   on the Library, uncombined with any other library facilities,
   conveyed under the terms of this License.

   b) Give prominent notice with the combined library that part of it
   is a work based on the Library, and explaining where to find the
   accompanying uncombined form of the same work.

  6. Revised Versions of the GNU Lesser General Public License.

  The Free Software Foundation may publish revised and/or new versions
of the GNU Lesser General Public License from time to time. Such new
versions will be similar in spirit to the present version, but may
differ in detail to address new problems or concerns.

  Each version is given a distinguishing version number. If the
Library as you received it specifies that a certain numbered version
of the GNU Lesser General Public License "or any later version"
applies to it, you have the option of following the terms and
conditions either of that published version or of any later version
published by the Free Software Foundation. If the Library as you
received it does not specify a version number of the GNU Lesser
General Public License, you may choose any version of the GNU Lesser
General Public License ever published by the Free Software Foundation.

  If the Library as you received it specifies that a proxy can decide
whether future versions of the GNU Lesser General Public License shall
apply, that proxy's public statement of acceptance of any version is
permanent authorization for you to choose that version for the
Library.

                    GNU GENERAL PUBLIC LICENSE
                       Version 3, 29 June 2007

 Copyright (C) 2007 Free Software Foundation, Inc. &lt;http://fsf.org/&gt;
 Everyone is permitted to copy and distribute verbatim copies
 of this license document, but changing it is not allowed.

                            Preamble

  The GNU General Public License is a free, copyleft license for
software and other kinds of works.

  The licenses for most software and other practical works are designed
to take away your freedom to share and change the works.  By contrast,
the GNU General Public License is intended to guarantee your freedom to
share and change all versions of a program--to make sure it remains free
software for all its users.  We, the Free Software Foundation, use the
GNU General Public License for most of our software; it applies also to
any other work released this way by its authors.  You can apply it to
your programs, too.

  When we speak of free software, we are referring to freedom, not
price.  Our General Public Licenses are designed to make sure that you
have the freedom to distribute copies of free software (and charge for
them if you wish), that you receive source code or can get it if you
want it, that you can change the software or use pieces of it in new
free programs, and that you know you can do these things.

  To protect your rights, we need to prevent others from denying you
these rights or asking you to surrender the rights.  Therefore, you have
certain responsibilities if you distribute copies of the software, or if
you modify it: responsibilities to respect the freedom of others.

  For example, if you distribute copies of such a program, whether
gratis or for a fee, you must pass on to the recipients the same
freedoms that you received.  You must make sure that they, too, receive
or can get the source code.  And you must show them these terms so they
know their rights.

  Developers that use the GNU GPL protect your rights with two steps:
(1) assert copyright on the software, and (2) offer you this License
giving you legal permission to copy, distribute and/or modify it.

  For the developers' and authors' protection, the GPL clearly explains
that there is no warranty for this free software.  For both users' and
authors' sake, the GPL requires that modified versions be marked as
changed, so that their problems will not be attributed erroneously to
authors of previous versions.

  Some devices are designed to deny users access to install or run
modified versions of the software inside them, although the manufacturer
can do so.  This is fundamentally incompatible with the aim of
protecting users' freedom to change the software.  The systematic
pattern of such abuse occurs in the area of products for individuals to
use, which is precisely where it is most unacceptable.  Therefore, we
have designed this version of the GPL to prohibit the practice for those
products.  If such problems arise substantially in other domains, we
stand ready to extend this provision to those domains in future versions
of the GPL, as needed to protect the freedom of users.

  Finally, every program is threatened constantly by software patents.
States should not allow patents to restrict development and use of
software on general-purpose computers, but in those that do, we wish to
avoid the special danger that patents applied to a free program could
make it effectively proprietary.  To prevent this, the GPL assures that
patents cannot be used to render the program non-free.

  The precise terms and conditions for copying, distribution and
modification follow.

                       TERMS AND CONDITIONS

  0. Definitions.

  "This License" refers to version 3 of the GNU General Public License.

  "Copyright" also means copyright-like laws that apply to other kinds of
works, such as semiconductor masks.

  "The Program" refers to any copyrightable work licensed under this
License.  Each licensee is addressed as "you".  "Licensees" and
"recipients" may be individuals or organizations.

  To "modify" a work means to copy from or adapt all or part of the work
in a fashion requiring copyright permission, other than the making of an
exact copy.  The resulting work is called a "modified version" of the
earlier work or a work "based on" the earlier work.

  A "covered work" means either the unmodified Program or a work based
on the Program.

  To "propagate" a work means to do anything with it that, without
permission, would make you directly or secondarily liable for
infringement under applicable copyright law, except executing it on a
computer or modifying a private copy.  Propagation includes copying,
distribution (with or without modification), making available to the
public, and in some countries other activities as well.

  To "convey" a work means any kind of propagation that enables other
parties to make or receive copies.  Mere interaction with a user through
a computer network, with no transfer of a copy, is not conveying.

  An interactive user interface displays "Appropriate Legal Notices"
to the extent that it includes a convenient and prominently visible
feature that (1) displays an appropriate copyright notice, and (2)
tells the user that there is no warranty for the work (except to the
extent that warranties are provided), that licensees may convey the
work under this License, and how to view a copy of this License.  If
the interface presents a list of user commands or options, such as a
menu, a prominent item in the list meets this criterion.

  1. Source Code.

  The "source code" for a work means the preferred form of the work
for making modifications to it.  "Object code" means any non-source
form of a work.

  A "Standard Interface" means an interface that either is an official
standard defined by a recognized standards body, or, in the case of
interfaces specified for a particular programming language, one that
is widely used among developers working in that language.

  The "System Libraries" of an executable work include anything, other
than the work as a whole, that (a) is included in the normal form of
packaging a Major Component, but which is not part of that Major
Component, and (b) serves only to enable use of the work with that
Major Component, or to implement a Standard Interface for which an
implementation is available to the public in source code form.  A
"Major Component", in this context, means a major essential component
(kernel, window system, and so on) of the specific operating system
(if any) on which the executable work runs, or a compiler used to
produce the work, or an object code interpreter used to run it.

  The "Corresponding Source" for a work in object code form means all
the source code needed to generate, install, and (for an executable
work) run the object code and to modify the work, including scripts to
control those activities.  However, it does not include the work's
System Libraries, or general-purpose tools or generally available free
programs which are used unmodified in performing those activities but
which are not part of the work.  For example, Corresponding Source
includes interface definition files associated with source files for
the work, and the source code for shared libraries and dynamically
linked subprograms that the work is specifically designed to require,
such as by intimate data communication or control flow between those
subprograms and other parts of the work.

  The Corresponding Source need not include anything that users
can regenerate automatically from other parts of the Corresponding
Source.

  The Corresponding Source for a work in source code form is that
same work.

  2. Basic Permissions.

  All rights granted under this License are granted for the term of
copyright on the Program, and are irrevocable provided the stated
conditions are met.  This License explicitly affirms your unlimited
permission to run the unmodified Program.  The output from running a
covered work is covered by this License only if the output, given its
content, constitutes a covered work.  This License acknowledges your
rights of fair use or other equivalent, as provided by copyright law.

  You may make, run and propagate covered works that you do not
convey, without conditions so long as your license otherwise remains
in force.  You may convey covered works to others for the sole purpose
of having them make modifications exclusively for you, or provide you
with facilities for running those works, provided that you comply with
the terms of this License in conveying all material for which you do
not control copyright.  Those thus making or running the covered works
for you must do so exclusively on your behalf, under your direction
and control, on terms that prohibit them from making any copies of
your copyrighted material outside their relationship with you.

  Conveying under any other circumstances is permitted solely under
the conditions stated below.  Sublicensing is not allowed; section 10
makes it unnecessary.

  3. Protecting Users' Legal Rights From Anti-Circumvention Law.

  No covered work shall be deemed part of an effective technological
measure under any applicable law fulfilling obligations under article
11 of the WIPO copyright treaty adopted on 20 December 1996, or
similar laws prohibiting or restricting circumvention of such
measures.

  When you convey a covered work, you waive any legal power to forbid
circumvention of technological measures to the extent such circumvention
is effected by exercising rights under this License with respect to
the covered work, and you disclaim any intention to limit operation or
modification of the work as a means of enforcing, against the work's
users, your or third parties' legal rights to forbid circumvention of
technological measures.

  4. Conveying Verbatim Copies.

  You may convey verbatim copies of the Program's source code as you
receive it, in any medium, provided that you conspicuously and
appropriately publish on each copy an appropriate copyright notice;
keep intact all notices stating that this License and any
non-permissive terms added in accord with section 7 apply to the code;
keep intact all notices of the absence of any warranty; and give all
recipients a copy of this License along with the Program.

  You may charge any price or no price for each copy that you convey,
and you may offer support or warranty protection for a fee.

  5. Conveying Modified Source Versions.

  You may convey a work based on the Program, or the modifications to
produce it from the Program, in the form of source code under the
terms of section 4, provided that you also meet all of these conditions:

    a) The work must carry prominent notices stating that you modified
    it, and giving a relevant date.

    b) The work must carry prominent notices stating that it is
    released under this License and any conditions added under section
    7.  This requirement modifies the requirement in section 4 to
    "keep intact all notices".

    c) You must license the entire work, as a whole, under this
    License to anyone who comes into possession of a copy.  This
    License will therefore apply, along with any applicable section 7
    additional terms, to the whole of the work, and all its parts,
    regardless of how they are packaged.  This License gives no
    permission to license the work in any other way, but it does not
    invalidate such permission if you have separately received it.

    d) If the work has interactive user interfaces, each must display
    Appropriate Legal Notices; however, if the Program has interactive
    interfaces that do not display Appropriate Legal Notices, your
    work need not make them do so.

  A compilation of a covered work with other separate and independent
works, which are not by their nature extensions of the covered work,
and which are not combined with it such as to form a larger program,
in or on a volume of a storage or distribution medium, is called an
"aggregate" if the compilation and its resulting copyright are not
used to limit the access or legal rights of the compilation's users
beyond what the individual works permit.  Inclusion of a covered work
in an aggregate does not cause this License to apply to the other
parts of the aggregate.

  6. Conveying Non-Source Forms.

  You may convey a covered work in object code form under the terms
of sections 4 and 5, provided that you also convey the
machine-readable Corresponding Source under the terms of this License,
in one of these ways:

    a) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by the
    Corresponding Source fixed on a durable physical medium
    customarily used for software interchange.

    b) Convey the object code in, or embodied in, a physical product
    (including a physical distribution medium), accompanied by a
    written offer, valid for at least three years and valid for as
    long as you offer spare parts or customer support for that product
    model, to give anyone who possesses the object code either (1) a
    copy of the Corresponding Source for all the software in the
    product that is covered by this License, on a durable physical
    medium customarily used for software interchange, for a price no
    more than your reasonable cost of physically performing this
    conveying of source, or (2) access to copy the
    Corresponding Source from a network server at no charge.

    c) Convey individual copies of the object code with a copy of the
    written offer to provide the Corresponding Source.  This
    alternative is allowed only occasionally and noncommercially, and
    only if you received the object code with such an offer, in accord
    with subsection 6b.

    d) Convey the object code by offering access from a designated
    place (gratis or for a charge), and offer equivalent access to the
    Corresponding Source in the same way through the same place at no
    further charge.  You need not require recipients to copy the
    Corresponding Source along with the object code.  If the place to
    copy the object code is a network server, the Corresponding Source
    may be on a different server (operated by you or a third party)
    that supports equivalent copying facilities, provided you maintain
    clear directions next to the object code saying where to find the
    Corresponding Source.  Regardless of what server hosts the
    Corresponding Source, you remain obligated to ensure that it is
    available for as long as needed to satisfy these requirements.

    e) Convey the object code using peer-to-peer transmission, provided
    you inform other peers where the object code and Corresponding
    Source of the work are being offered to the general public at no
    charge under subsection 6d.

  A separable portion of the object code, whose source code is excluded
from the Corresponding Source as a System Library, need not be
included in conveying the object code work.

  A "User Product" is either (1) a "consumer product", which means any
tangible personal property which is normally used for personal, family,
or household purposes, or (2) anything designed or sold for incorporation
into a dwelling.  In determining whether a product is a consumer product,
doubtful cases shall be resolved in favor of coverage.  For a particular
product received by a particular user, "normally used" refers to a
typical or common use of that class of product, regardless of the status
of the particular user or of the way in which the particular user
actually uses, or expects or is expected to use, the product.  A product
is a consumer product regardless of whether the product has substantial
commercial, industrial or non-consumer uses, unless such uses represent
the only significant mode of use of the product.

  "Installation Information" for a User Product means any methods,
procedures, authorization keys, or other information required to install
and execute modified versions of a covered work in that User Product from
a modified version of its Corresponding Source.  The information must
suffice to ensure that the continued functioning of the modified object
code is in no case prevented or interfered with solely because
modification has been made.

  If you convey an object code work under this section in, or with, or
specifically for use in, a User Product, and the conveying occurs as
part of a transaction in which the right of possession and use of the
User Product is transferred to the recipient in perpetuity or for a
fixed term (regardless of how the transaction is characterized), the
Corresponding Source conveyed under this section must be accompanied
by the Installation Information.  But this requirement does not apply
if neither you nor any third party retains the ability to install
modified object code on the User Product (for example, the work has
been installed in ROM).

  The requirement to provide Installation Information does not include a
requirement to continue to provide support service, warranty, or updates
for a work that has been modified or installed by the recipient, or for
the User Product in which it has been modified or installed.  Access to a
network may be denied when the modification itself materially and
adversely affects the operation of the network or violates the rules and
protocols for communication across the network.

  Corresponding Source conveyed, and Installation Information provided,
in accord with this section must be in a format that is publicly
documented (and with an implementation available to the public in
source code form), and must require no special password or key for
unpacking, reading or copying.

  7. Additional Terms.

  "Additional permissions" are terms that supplement the terms of this
License by making exceptions from one or more of its conditions.
Additional permissions that are applicable to the entire Program shall
be treated as though they were included in this License, to the extent
that they are valid under applicable law.  If additional permissions
apply only to part of the Program, that part may be used separately
under those permissions, but the entire Program remains governed by
this License without regard to the additional permissions.

  When you convey a copy of a covered work, you may at your option
remove any additional permissions from that copy, or from any part of
it.  (Additional permissions may be written to require their own
removal in certain cases when you modify the work.)  You may place
additional permissions on material, added by you to a covered work,
for which you have or can give appropriate copyright permission.

  Notwithstanding any other provision of this License, for material you
add to a covered work, you may (if authorized by the copyright holders of
that material) supplement the terms of this License with terms:

    a) Disclaiming warranty or limiting liability differently from the
    terms of sections 15 and 16 of this License; or

    b) Requiring preservation of specified reasonable legal notices or
    author attributions in that material or in the Appropriate Legal
    Notices displayed by works containing it; or

    c) Prohibiting misrepresentation of the origin of that material, or
    requiring that modified versions of such material be marked in
    reasonable ways as different from the original version; or

    d) Limiting the use for publicity purposes of names of licensors or
    authors of the material; or

    e) Declining to grant rights under trademark law for use of some
    trade names, trademarks, or service marks; or

    f) Requiring indemnification of licensors and authors of that
    material by anyone who conveys the material (or modified versions of
    it) with contractual assumptions of liability to the recipient, for
    any liability that these contractual assumptions directly impose on
    those licensors and authors.

  All other non-permissive additional terms are considered "further
restrictions" within the meaning of section 10.  If the Program as you
received it, or any part of it, contains a notice stating that it is
governed by this License along with a term that is a further
restriction, you may remove that term.  If a license document contains
a further restriction but permits relicensing or conveying under this
License, you may add to a covered work material governed by the terms
of that license document, provided that the further restriction does
not survive such relicensing or conveying.

  If you add terms to a covered work in accord with this section, you
must place, in the relevant source files, a statement of the
additional terms that apply to those files, or a notice indicating
where to find the applicable terms.

  Additional terms, permissive or non-permissive, may be stated in the
form of a separately written license, or stated as exceptions;
the above requirements apply either way.

  8. Termination.

  You may not propagate or modify a covered work except as expressly
provided under this License.  Any attempt otherwise to propagate or
modify it is void, and will automatically terminate your rights under
this License (including any patent licenses granted under the third
paragraph of section 11).

  However, if you cease all violation of this License, then your
license from a particular copyright holder is reinstated (a)
provisionally, unless and until the copyright holder explicitly and
finally terminates your license, and (b) permanently, if the copyright
holder fails to notify you of the violation by some reasonable means
prior to 60 days after the cessation.

  Moreover, your license from a particular copyright holder is
reinstated permanently if the copyright holder notifies you of the
violation by some reasonable means, this is the first time you have
received notice of violation of this License (for any work) from that
copyright holder, and you cure the violation prior to 30 days after
your receipt of the notice.

  Termination of your rights under this section does not terminate the
licenses of parties who have received copies or rights from you under
this License.  If your rights have been terminated and not permanently
reinstated, you do not qualify to receive new licenses for the same
material under section 10.

  9. Acceptance Not Required for Having Copies.

  You are not required to accept this License in order to receive or
run a copy of the Program.  Ancillary propagation of a covered work
occurring solely as a consequence of using peer-to-peer transmission
to receive a copy likewise does not require acceptance.  However,
nothing other than this License grants you permission to propagate or
modify any covered work.  These actions infringe copyright if you do
not accept this License.  Therefore, by modifying or propagating a
covered work, you indicate your acceptance of this License to do so.

  10. Automatic Licensing of Downstream Recipients.

  Each time you convey a covered work, the recipient automatically
receives a license from the original licensors, to run, modify and
propagate that work, subject to this License.  You are not responsible
for enforcing compliance by third parties with this License.

  An "entity transaction" is a transaction transferring control of an
organization, or substantially all assets of one, or subdividing an
organization, or merging organizations.  If propagation of a covered
work results from an entity transaction, each party to that
transaction who receives a copy of the work also receives whatever
licenses to the work the party's predecessor in interest had or could
give under the previous paragraph, plus a right to possession of the
Corresponding Source of the work from the predecessor in interest, if
the predecessor has it or can get it with reasonable efforts.

  You may not impose any further restrictions on the exercise of the
rights granted or affirmed under this License.  For example, you may
not impose a license fee, royalty, or other charge for exercise of
rights granted under this License, and you may not initiate litigation
(including a cross-claim or counterclaim in a lawsuit) alleging that
any patent claim is infringed by making, using, selling, offering for
sale, or importing the Program or any portion of it.

  11. Patents.

  A "contributor" is a copyright holder who authorizes use under this
License of the Program or a work on which the Program is based.  The
work thus licensed is called the contributor's "contributor version".

  A contributor's "essential patent claims" are all patent claims
owned or controlled by the contributor, whether already acquired or
hereafter acquired, that would be infringed by some manner, permitted
by this License, of making, using, or selling its contributor version,
but do not include claims that would be infringed only as a
consequence of further modification of the contributor version.  For
purposes of this definition, "control" includes the right to grant
patent sublicenses in a manner consistent with the requirements of
this License.

  Each contributor grants you a non-exclusive, worldwide, royalty-free
patent license under the contributor's essential patent claims, to
make, use, sell, offer for sale, import and otherwise run, modify and
propagate the contents of its contributor version.

  In the following three paragraphs, a "patent license" is any express
agreement or commitment, however denominated, not to enforce a patent
(such as an express permission to practice a patent or covenant not to
sue for patent infringement).  To "grant" such a patent license to a
party means to make such an agreement or commitment not to enforce a
patent against the party.

  If you convey a covered work, knowingly relying on a patent license,
and the Corresponding Source of the work is not available for anyone
to copy, free of charge and under the terms of this License, through a
publicly available network server or other readily accessible means,
then you must either (1) cause the Corresponding Source to be so
available, or (2) arrange to deprive yourself of the benefit of the
patent license for this particular work, or (3) arrange, in a manner
consistent with the requirements of this License, to extend the patent
license to downstream recipients.  "Knowingly relying" means you have
actual knowledge that, but for the patent license, your conveying the
covered work in a country, or your recipient's use of the covered work
in a country, would infringe one or more identifiable patents in that
country that you have reason to believe are valid.

  If, pursuant to or in connection with a single transaction or
arrangement, you convey, or propagate by procuring conveyance of, a
covered work, and grant a patent license to some of the parties
receiving the covered work authorizing them to use, propagate, modify
or convey a specific copy of the covered work, then the patent license
you grant is automatically extended to all recipients of the covered
work and works based on it.

  A patent license is "discriminatory" if it does not include within
the scope of its coverage, prohibits the exercise of, or is
conditioned on the non-exercise of one or more of the rights that are
specifically granted under this License.  You may not convey a covered
work if you are a party to an arrangement with a third party that is
in the business of distributing software, under which you make payment
to the third party based on the extent of your activity of conveying
the work, and under which the third party grants, to any of the
parties who would receive the covered work from you, a discriminatory
patent license (a) in connection with copies of the covered work
conveyed by you (or copies made from those copies), or (b) primarily
for and in connection with specific products or compilations that
contain the covered work, unless you entered into that arrangement,
or that patent license was granted, prior to 28 March 2007.

  Nothing in this License shall be construed as excluding or limiting
any implied license or other defenses to infringement that may
otherwise be available to you under applicable patent law.

  12. No Surrender of Others' Freedom.

  If conditions are imposed on you (whether by court order, agreement or
otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot convey a
covered work so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you may
not convey it at all.  For example, if you agree to terms that obligate you
to collect a royalty for further conveying from those to whom you convey
the Program, the only way you could satisfy both those terms and this
License would be to refrain entirely from conveying the Program.

  13. Use with the GNU Affero General Public License.

  Notwithstanding any other provision of this License, you have
permission to link or combine any covered work with a work licensed
under version 3 of the GNU Affero General Public License into a single
combined work, and to convey the resulting work.  The terms of this
License will continue to apply to the part which is the covered work,
but the special requirements of the GNU Affero General Public License,
section 13, concerning interaction through a network will apply to the
combination as such.

  14. Revised Versions of this License.

  The Free Software Foundation may publish revised and/or new versions of
the GNU General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to
address new problems or concerns.

  Each version is given a distinguishing version number.  If the
Program specifies that a certain numbered version of the GNU General
Public License "or any later version" applies to it, you have the
option of following the terms and conditions either of that numbered
version or of any later version published by the Free Software
Foundation.  If the Program does not specify a version number of the
GNU General Public License, you may choose any version ever published
by the Free Software Foundation.

  If the Program specifies that a proxy can decide which future
versions of the GNU General Public License can be used, that proxy's
public statement of acceptance of a version permanently authorizes you
to choose that version for the Program.

  Later license versions may give you additional or different
permissions.  However, no additional obligations are imposed on any
author or copyright holder as a result of your choosing to follow a
later version.

  15. Disclaimer of Warranty.

  THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY
APPLICABLE LAW.  EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT
HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY
OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE.  THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM
IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF
ALL NECESSARY SERVICING, REPAIR OR CORRECTION.

  16. Limitation of Liability.

  IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MODIFIES AND/OR CONVEYS
THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY
GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE
USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF
DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD
PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS),
EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF
SUCH DAMAGES.

  17. Interpretation of Sections 15 and 16.

  If the disclaimer of warranty and limitation of liability provided
above cannot be given local legal effect according to their terms,
reviewing courts shall apply local law that most closely approximates
an absolute waiver of all civil liability in connection with the
Program, unless a warranty or assumption of liability accompanies a
copy of the Program in return for a fee.

                     END OF TERMS AND CONDITIONS
</pre>
EOF;
	echo $lang->sprintf($lang->license_step, $license);
	$output->print_footer('requirements_check');
}

/**
 * Check our requirements
 */
function requirements_check()
{
 	global $dboptions, $output, $lang;

	$action = "requirements_check";
     $output->print_header($lang->req_check, 'requirements');
    echo $lang->req_step_top;
	$errors = array();
	$showerror = 0;

	if(!file_exists(PBB_ROOT."/includes/config.php"))
	{
		if(!@rename(PBB_ROOT."/includes/config.default.php", PBB_ROOT."/includes/config.php"))
		{
			if(!$configwritable)
			{
				$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_configdefaultfile);
				$configstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
				$showerror = 1;
			}
		}
	}

	// Check PHP Version
	if(version_compare(PHP_VERSION, '5.2.0', "<"))
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->sprintf($lang->req_step_error_phpversion, PHP_VERSION));
		$phpversion = $lang->sprintf($lang->req_step_span_fail, PHP_VERSION);
		$showerror = 1;
	}
	else
	{
		$phpversion = $lang->sprintf($lang->req_step_span_pass, PHP_VERSION);
	}

	$mboptions = array();

	if(function_exists('mb_detect_encoding'))
	{
		$mboptions[] = $lang->multi_byte;
	}

	if(function_exists('iconv'))
	{
		$mboptions[] = 'iconv';
	}

	// Check Multibyte extensions
	if(count($mboptions) < 1)
	{
		$mbstatus = $lang->sprintf($lang->req_step_span_fail, $lang->none);
	}
	else
	{
		$mbstatus = implode(', ', $mboptions);
	}

	// Check database engines
	if(count($dboptions) < 1)
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_dboptions);
		$dbsupportlist = $lang->sprintf($lang->req_step_span_fail, $lang->none);
		$showerror = 1;
	}
	else
	{
		foreach($dboptions as $dboption)
		{
			$dbsupportlist[] = $dboption['title'];
		}
		$dbsupportlist = implode(', ', $dbsupportlist);
	}

	// Check XML parser is installed
	if(!function_exists('xml_parser_create'))
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_xmlsupport);
		$xmlstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_installed);
		$showerror = 1;
	}
	else
	{
		$xmlstatus = $lang->sprintf($lang->req_step_span_pass, $lang->installed);
	}

	// Check config file is writable
	$configwritable = @fopen(PBB_ROOT.'includes/config.php', 'w');
	if(!$configwritable)
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_configfile);
		$configstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
		$showerror = 1;
	}
	else
	{
		$configstatus = $lang->sprintf($lang->req_step_span_pass, $lang->writable);
	}
	@fclose($configwritable);
	// Check settings file is writable
	$settingswritable = @fopen(PBB_ROOT.'includes/settings.php', 'w');
	if(!$settingswritable)
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_settingsfile);
		$settingsstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
		$showerror = 1;
	}
	else
	{
		$settingsstatus = $lang->sprintf($lang->req_step_span_pass, $lang->writable);
	}
	@fclose($settingswritable);

	// Check cache directory is writable
	$cachewritable = @fopen(PBB_ROOT.'cache/test.write', 'w');
	if(!$cachewritable)
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_cachedir);
		$cachestatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
		$showerror = 1;
		@fclose($cachewritable);
	}
	else
	{
		$cachestatus = $lang->sprintf($lang->req_step_span_pass, $lang->writable);
		@fclose($cachewritable);
	  	@my_chmod(PBB_ROOT.'cache', '0777');
	  	@my_chmod(PBB_ROOT.'cache/test.write', '0777');
		@unlink(PBB_ROOT.'cache/test.write');
	}

	// Check upload directory is writable
	$uploadswritable = @fopen(PBB_ROOT.'download/test.write', 'w');
	if(!$uploadswritable)
	{
		$errors[] = $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_uploaddir);
		$uploadsstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
		$showerror = 1;
		@fclose($uploadswritable);
	}
	else
	{
		$uploadsstatus = $lang->sprintf($lang->req_step_span_pass, $lang->writable);
		@fclose($uploadswritable);
	  	@my_chmod(PBB_ROOT.'download', '0777');
	  	@my_chmod(PBB_ROOT.'download/test.write', '0777');
		@unlink(PBB_ROOT.'download/test.write');
	}

	// Check avatar directory is writable
	$avatarswritable = @fopen(PBB_ROOT.'download/avatar/test.write', 'w');
	if(!$avatarswritable)
	{
		$errors[] =  $lang->sprintf($lang->req_step_error_box, $lang->req_step_error_avatardir);
		$avatarsstatus = $lang->sprintf($lang->req_step_span_fail, $lang->not_writable);
		$showerror = 1;
		@fclose($avatarswritable);
	}
	else
	{
		$avatarsstatus = $lang->sprintf($lang->req_step_span_pass, $lang->writable);
		@fclose($avatarswritable);
		@my_chmod(PBB_ROOT.'download/avatar', '0777');
	  	@my_chmod(PBB_ROOT.'download/avatar/test.write', '0777');
		@unlink(PBB_ROOT.'download/avatar/test.write');
  	}

	// Output requirements page
	echo $lang->sprintf($lang->req_step_reqtable, $phpversion, $dbsupportlist, $mbstatus, $xmlstatus, $configstatus, $settingsstatus, $cachestatus, $uploadsstatus, $avatarsstatus);

	if($showerror == 1)
	{
		$error_list = error_list($errors);
       echo $lang->sprintf($lang->req_step_error_tablelist, $error_list);
		echo "\n			<input type=\"hidden\" name=\"action\" value=\"requirements_check\" />";
		echo "\n				<div id=\"next_button\"><input type=\"submit\" class=\"submit_button\" value=\"Recheck &raquo;\" /></div><br style=\"clear: both;\" />\n";
		$output->print_footer();
	}
	else
	{
        echo $lang->req_step_reqcomplete;
		$output->print_footer('database_info');
	}
}

/**
 * Which database do we use?
 */
function database_info()
{
	global $dbinfo, $errors, $dboptions, $PBBoard, $output, $lang;

	$action = "database_info";
    $output->print_header($lang->db_config, 'dbconfig');

	echo "<script type=\"text/javascript\">
		function updateDBSettings()
		{
			var dbengine = \$(\"#dbengine\").val();
			$('.db_settings').each(function()
			{
				var element = $(this);
				element.addClass('db_settings');
				if(dbengine+'_settings' == element.attr('id'))
				{
					element.show();
				}
				else
				{
					element.hide();
				}
			});
		}
		$(function()
		{
			updateDBSettings();
		});
		</script>";

	// Check for errors from this stage
	if(is_array($errors))
	{
		$error_list = error_list($errors);
        echo $lang->sprintf($lang->db_step_error_config, $error_list);
	}
	else
	{
       echo $lang->db_step_config_db;
	}

   	$dbengines = "<option value=\"mysqli\"> MySQLi </option>";

	// Loop through database engines
	/*
	foreach($dboptions as $dbfile => $dbtype)
	{
		if($PBBoard->get_input('dbengine') == $dbfile)
		{
			$dbengines .= "<option value=\"{$dbfile}\" selected=\"selected\">{$dbtype['title']}</option>";
		}
		else
		{
			$dbengines .= "<option value=\"{$dbfile}\">{$dbtype['title']}</option>";
		}
	}
   */
	$db_info = array();
	foreach($dboptions as $dbfile => $dbtype)
	{
		require_once PBB_ROOT."includes/db_{$dbfile}.php";
		$db = new $dbtype['class'];
		$encodings = $db->fetch_db_charsets();
		$encoding_select = '';
		$PBBoard->input['config'] = $PBBoard->get_input('config', 2);
		if(empty($PBBoard->input['config'][$dbfile]['dbhost']))
		{
			$PBBoard->input['config'][$dbfile]['dbhost'] = "localhost";
		}
		if(empty($PBBoard->input['config'][$dbfile]['tableprefix']))
		{
			$PBBoard->input['config'][$dbfile]['tableprefix'] = "pbb_";
		}
		if(empty($PBBoard->input['config'][$dbfile]['dbname']))
		{
			$PBBoard->input['config'][$dbfile]['dbname'] = '';
		}
		if(empty($PBBoard->input['config'][$dbfile]['dbuser']))
		{
			$PBBoard->input['config'][$dbfile]['dbuser'] = '';
		}
		if(empty($PBBoard->input['config'][$dbfile]['dbpass']))
		{
			$PBBoard->input['config'][$dbfile]['dbpass'] = '';
		}
		if(empty($PBBoard->input['config'][$dbfile]['encoding']))
		{
			$PBBoard->input['config'][$dbfile]['encoding'] = "utf8mb4";
		}

		$class = '';
		if(empty($first) && !$PBBoard->get_input('dbengine'))
		{
			$PBBoard->input['dbengine'] = $dbfile;
			$first = true;
		}
		if($dbfile == $PBBoard->input['dbengine'])
		{
			$class = "_selected";
		}

		$db_info[$dbfile] = "
			<tbody id=\"{$dbfile}_settings\" class=\"db_settings db_type{$class}\">
				<tr>
					<th colspan=\"2\" class=\"first last\">{$dbtype['title']} {$lang->database_settings}</th>
				</tr>";

		// SQLite gets some special settings
		if($dbfile == 'sqlite')
		{
			$db_info[$dbfile] .= "
				<tr class=\"alt_row\">
					<td class=\"first\"><label for=\"config_{$dbfile}_dbname\">{$lang->database_path}</label></td>
					<td class=\"last alt_col\"><input type=\"text\" class=\"text_input\" name=\"config[{$dbfile}][dbname]\" id=\"config_{$dbfile}_dbname\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['dbname'])."\" /></td>
				</tr>";
		}
		// Others get db host, username, password etc
		else
		{
			$db_info[$dbfile] .= "
				<tr class=\"alt_row\">
					<td class=\"first\"><label for=\"config_{$dbfile}_dbhost\">{$lang->database_host}</label></td>
					<td class=\"last alt_col\"><input type=\"text\" class=\"text_input\" name=\"config[{$dbfile}][dbhost]\" id=\"config_{$dbfile}_dbhost\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['dbhost'])."\" /></td>
				</tr>
				<tr>
					<td class=\"first\"><label for=\"config_{$dbfile}_dbuser\">{$lang->database_user}</label></td>
					<td class=\"last alt_col\"><input type=\"text\" class=\"text_input\" name=\"config[{$dbfile}][dbuser]\" id=\"config_{$dbfile}_dbuser\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['dbuser'])."\" /></td>
				</tr>
				<tr class=\"alt_row\">
					<td class=\"first\"><label for=\"config_{$dbfile}_dbpass\">{$lang->database_pass}</label></td>
					<td class=\"last alt_col\"><input type=\"password\" class=\"text_input\" name=\"config[{$dbfile}][dbpass]\" id=\"config_{$dbfile}_dbpass\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['dbpass'])."\" /></td>
				</tr>
				<tr class=\"last\">
					<td class=\"first\"><label for=\"config_{$dbfile}_dbname\">{$lang->database_name}</label></td>
					<td class=\"last alt_col\"><input type=\"text\" class=\"text_input\" name=\"config[{$dbfile}][dbname]\" id=\"config_{$dbfile}_dbname\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['dbname'])."\" /></td>
				</tr>";
		}

		// Now we're up to table settings
		$db_info[$dbfile] .= "
			<tr>
				<th colspan=\"2\" class=\"first last\">{$lang->table_settings}</th>
			</tr>
			<tr class=\"first\">
				<td class=\"first\"><label for=\"config_{$dbfile}_tableprefix\">{$lang->table_prefix}</label></td>
				<td class=\"last alt_col\"><input type=\"text\" class=\"text_input\" name=\"config[{$dbfile}][tableprefix]\" id=\"config_{$dbfile}_tableprefix\" value=\"".htmlspecialchars_uni($PBBoard->input['config'][$dbfile]['tableprefix'])."\" /></td>
			</tr>
			";

		// Encoding selection only if supported

		if(is_array($encodings))
		{
			$select_options = "";
			foreach($encodings as $encoding => $title)
			{
				if($PBBoard->input['config'][$dbfile]['encoding'] == $encoding)
				{
					$select_options .= "<option value=\"{$encoding}\" selected=\"selected\">{$title}</option>";
				}
				else
				{
					$select_options .= "<option value=\"{$encoding}\">{$title}</option>";
				}
			}
			$db_info[$dbfile] .= "
				<tr class=\"last\">
					<td class=\"first\"><label for=\"config_{$dbfile}_encoding\">{$lang->table_encoding}</label></td>
					<td class=\"last alt_col\"><select name=\"config[{$dbfile}][encoding]\" id=\"config_{$dbfile}_encoding\">{$select_options}</select></td>
				</tr>
				</tbody>";
		}

	}
	$dbconfig = implode("", $db_info);

  echo $lang->sprintf($lang->db_step_config_table, $dbengines, $dbconfig);
	$output->print_footer('create_tables');
}



/**
 * Create our tables
 */
function create_tables()
{
	global $output, $dbinfo, $errors, $PBBoard, $dboptions, $lang;

	$PBBoard->input['dbengine'] = $PBBoard->get_input('dbengine');
	if(!file_exists(PBB_ROOT."includes/db_{$PBBoard->input['dbengine']}.php"))
	{
		$errors[] = $lang->db_step_error_invalidengine;
		database_info();
	}

	$PBBoard->input['config'] = $PBBoard->get_input('config', PBBoard::INPUT_ARRAY);
	$config = $PBBoard->input['config'][$PBBoard->input['dbengine']];

	if(strstr($PBBoard->input['dbengine'], "sqlite") !== false)
	{
		if(strstr($config['dbname'], "./") !== false || strstr($config['dbname'], "../") !== false || empty($config['dbname']))
		{
			$errors[] = $lang->db_step_error_sqlite_invalid_dbname;
			database_info();
		}
	}

	// Attempt to connect to the db
	require_once PBB_ROOT."includes/db_{$PBBoard->input['dbengine']}.php";
	switch($PBBoard->input['dbengine'])
	{
		case "mysqli":
			$db = new DB_MySQLi;
			break;
		default:
			$db = new DB_MySQL;
	}
 	$db->error_reporting = 0;

	$connect_array = array(
		"hostname" => $config['dbhost'],
		"username" => $config['dbuser'],
		"password" => $config['dbpass'],
		"database" => $config['dbname'],
		"encoding" => $config['encoding']
	);

	$connection = $db->connect($connect_array);
	if($connection === false)
	{
		$errors[] = $lang->sprintf($lang->db_step_error_noconnect, $config['dbhost']);
	}
	// double check if the DB exists for MySQL
	elseif(method_exists($db, 'select_db') && !$db->select_db($config['dbname']))
	{
		$errors[] = $lang->sprintf($lang->db_step_error_nodbname, $config['dbname']);
	}

	// Most DB engines only allow certain characters in the table name. Oracle requires an alphabetic character first.
	if((!preg_match("#^[A-Za-z][A-Za-z0-9_]*$#", $config['tableprefix'])) && $config['tableprefix'] != '')
	{
		$errors[] = $lang->db_step_error_invalid_tableprefix;
	}

	// Needs to be smaller then 64 characters total (MySQL Limit).
	// This allows 24 characters for the actual table name, which should be sufficient.
	if(strlen($config['tableprefix']) > 40)
	{
		$errors[] = $lang->db_step_error_tableprefix_too_long;
	}

	if(($db->engine == 'mysql' || $db->engine == 'mysqli') && $config['encoding'] == 'utf8mb4' && version_compare($db->get_version(), '5.5.3', '<'))
	{
		$errors[] = $lang->db_step_error_utf8mb4_error;
	}

	if(is_array($errors))
	{
		database_info();
	}

	// Decide if we can use a database encoding or not
	if($db->fetch_db_charsets() != false)
	{
		$db_encoding = "\$config['db']['encoding'] = '{$config['encoding']}';";
	}
	else
	{
		$db_encoding = "// \$config['db']['encoding'] = '{$config['encoding']}';";
	}

	$config['dbpass'] = addslashes($config['dbpass']);

	// Write the configuration file
	$configdata = "<?php
/**
 * DATABASE TYPE
 *
 * Please see the PBBoard Docs for advanced
 * database configuration for larger installations
 * https://pbboard.info/
 */

\$config['db']['name'] = '{$config['dbname']}';
\$config['db']['server'] = '{$config['dbhost']}';
\$config['db']['username'] = '{$config['dbuser']}';
\$config['db']['password'] = '{$config['dbpass']}';
\$config['db']['prefix'] = '{$config['tableprefix']}';
\$config['db']['dbtype'] = '{$PBBoard->input['dbengine']}';


/**
 * Admin CP directory
 *  For security reasons, it is recommended you
 *  rename your Admin CP directory. You then need
 *  to adjust the value below to point to the
 *  new directory.
 */

\$config['Misc']['admincpdir'] = 'admincp';

/**
 * To disable the plugin/hooks system
 */

\$config['HOOKS']['DISABLE_HOOKS'] = '1';

/**
 * Super Administrators
 *  A comma separated list of user IDs who cannot
 *  be edited, deleted or banned in the Admin CP.
 *  The administrator permissions for these users
 *  cannot be altered either.
 */

\$config['SpecialUsers']['superadministrators'] = '1';

/**
* The database encoding
*/

\$config['db']['encoding'] = 'utf8mb4';

?>";

	$file = fopen(PBB_ROOT.'includes/config.php', 'w');
	fwrite($file, $configdata);
	fclose($file);

	// Error reporting back on
 	$db->error_reporting = 1;

	$output->print_header($lang->table_creation, 'createtables');
	echo $lang->sprintf($lang->tablecreate_step_connected, $dboptions[$PBBoard->input['dbengine']]['short_title'], $db->get_version());

	if($dboptions[$PBBoard->input['dbengine']]['structure_file'])
	{
		$structure_file = $dboptions[$PBBoard->input['dbengine']]['structure_file'];
	}
	else
	{
		$structure_file = 'mysql_db_tables.php';
	}

	//change DEFAULT CHARACTER database to character utf8mb4_unicode_ci
  //$db->query("ALTER DATABASE ".$config['db']['name']." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

	require_once INSTALL_ROOT."resources/{$structure_file}";
	foreach($tables as $val)
	{
		$val = preg_replace('#pbb_(\S+?)([\s\.,\(]|$)#', $config['tableprefix'].'\\1\\2', $val);
		$val = str_replace("MyISAM","MyISAM CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci", $val);

		preg_match('#CREATE TABLE (\S+)(\s?|\(?)\(#i', $val, $match);
		if($match[1])
		{
			$db->drop_table($match[1], false, false);
			echo $lang->sprintf($lang->tablecreate_step_created, $match[1]);
		}
		$db->query($val);
		if($match[1])
		{
			echo $lang->done . "<br />\n";
		}
	}

	echo $lang->sprintf($lang->populate_step_insert);
	echo $lang->tablecreate_step_done;
	$output->print_footer('populate_tables');
}


 /**
 * Insert our default data
 */
function populate_tables()
{
	global $output, $dbinfo, $errors, $PBBoard, $dboptions, $lang;

	require PBB_ROOT.'includes/config.php';

	$db = db_connection($config);
	$output->print_header($lang->table_population, 'tablepopulate');

	if(!empty($dboptions[$db->type]['population_file']))
	{
		$population_file = $dboptions[$db->type]['population_file'];
	}
	else
	{
		$population_file = $mysql_db_inserts_file;
	}

	require_once INSTALL_ROOT."resources/{$population_file}";
	foreach($inserts as $val)
	{
		$val = preg_replace('#pbb_(\S+?)([\s\.,]|$)#', $config['db']['prefix'].'\\1\\2', $val);
		$db->query($val);
	}


	echo $lang->populate_step_inserted;
	$output->print_footer('templates');
}

/**
 * Install our theme
 */
function insert_templates()
{
	global $PBBoard, $output, $cache, $db, $lang;

   	$output->print_header($lang->theme_installation, 'theme');

	require PBB_ROOT.'includes/config.php';
	$db = db_connection($config);

        $contents = @file_get_contents(INSTALL_ROOT.'resources/PBBoard-Style.xml');

		preg_match_all('/<!\[CDATA\[(.*?)\]\]>/is', $contents, $match);
		foreach($match[0] as $val)
		{
		$contents = str_replace($val,base64_encode($val),$contents);
		}

		$import = xml_array($contents);
		$title = $import['styles_attr']['name'];
		$pbbversion = $import['styles_attr']['pbbversion'];
		$image_path = $import['styles_attr']['image_path'];
		$style_path = $import['styles_attr']['style_path'];
		$Templates = $import['styles']['templategroup'];
		$Templates_number = sizeof($import['styles']['templategroup']['template'])/2;


            	$styleid = '1';
                $product = 'PBBoard';
                $version = '3.0.4';
		        $x = 0;
     			while ($x < $Templates_number)
     			{
					$templatetitle = $Templates['template'][$x.'_attr']['name'];
					$TemplateArr = $db->query("SELECT * FROM ".$config['db']['prefix']."template WHERE styleid = 1 AND title = '$templatetitle'");
					$getstyle_row = $db->fetch_array($TemplateArr);

					$template = base64_decode($Templates['template'][$x]);
					$templatetype = $Templates['template'][$x.'_attr']['templatetype'];
					$dateline = $Templates['template'][$x.'_attr']['date'];

					$username = $Templates['template'][$x.'_attr']['username'];
					$template = str_replace("'", "&#39;", $template);
					$template = str_replace("//<![CDATA[", "", $template);
					$template = str_replace("//]]>", "", $template);
					$template = str_replace("<![CDATA[","", $template);
					$template = str_replace("]]>","", $template);

							$InsertTemplatesArr = array(
								'styleid' => $styleid,
								'title' => $templatetitle,
								'template' => $template,
								'template_un' => $template,
								'templatetype' => $templatetype,
								'dateline' => $dateline,
								'username' => $username,
								'version' => $version,
								'product' => $product
							);
	                    $Insert = $db->insert_query("template", $InsertTemplatesArr);


                     $x += 1;
     			}

        $deltemplates = $db->query("DELETE FROM " . $config['db']['prefix'] . "template WHERE styleid = '$styleid' and title = ''");

	echo $lang->languages_step_imported;
	$output->print_footer('languages');
}

/**
 * Install our lang
 */
function language()
{
	global $output, $PBBoard, $errors, $lang;

   	$output->print_header($lang->lang_lnsertion, 'languages');
   	if($_COOKIE['pbb_insall_lang'] == 'ar')
   	{
	$ar_lang =_InsertLang_ar();
	}
	elseif($_COOKIE['pbb_insall_lang'] == 'en')
    {
	$en_lang =_InsertLang_en();
	}
    echo $lang->languages_step_successfully;
	$output->print_footer('configuration');
}

 function _InsertLang_ar()
	{
	global $output, $PBBoard, $errors, $lang;

	require PBB_ROOT.'includes/config.php';
	$db = db_connection($config);

        $xml_code_ar = @file_get_contents(INSTALL_ROOT.'resources/ArabicLanguage.xml');
		$import = xml_array($xml_code_ar);
		$title = $import['language_attr']['name'];
		$pbbversion = $import['language_attr']['version'];
		$Languages = $import['language']['phrasegroup'];
		$language_number = sizeof($import['language']['phrasegroup']['phrase'])/2;

			$langid = '1';
		            $x = 0;
     			while ($x < $language_number)
     			{
						$varname = $Languages['phrase'][$x.'_attr']['name'];
						$fieldname = $Languages['phrase'][$x.'_attr']['fieldname'];
						$version = $Languages['phrase'][$x.'_attr']['pbbversion'];
						$text = $Languages['phrase'][$x];
						$product = $Languages['phrase'][$x.'_attr']['product'];
						$dateline = $Languages['phrase'][$x.'_attr']['date'];
						$username = $Languages['phrase'][$x.'_attr']['username'];
			            $text = str_replace("'", "&#39;", $text);

							$InsertLanguagesArr = array(
								'languageid' => $langid,
								'varname' => $varname,
								'fieldname' => $fieldname,
								'text' => $text,
								'dateline' => $dateline,
								'username' => $username,
								'version' => $version,
								'product' => $product
							);
	                    $Insert = $db->insert_query("phrase_language", $InsertLanguagesArr);
                     $x += 1;
     			}

           if ($Insert)
			{
                  $delLanguages_ar = $db->query("DELETE FROM " . $config['db']['prefix'] . "phrase_language WHERE languageid = '$langid' and varname = ''");
			}


		return ($Insert) ? true : false;
	}

 function _InsertLang_en()
	{
	global $output, $PBBoard, $errors , $lang;
	require PBB_ROOT.'includes/config.php';
	$db = db_connection($config);

        $xml_code_ar = @file_get_contents(INSTALL_ROOT.'resources/EnglishLanguage.xml');
		$import = xml_array($xml_code_ar);
		$title = $import['language_attr']['name'];
		$pbbversion = $import['language_attr']['version'];
		$Languages = $import['language']['phrasegroup'];
		$language_number = sizeof($import['language']['phrasegroup']['phrase'])/2;

			$langid_en = '1';
		            $x = 0;
     			while ($x < $language_number)
     			{
						$varname = $Languages['phrase'][$x.'_attr']['name'];
						$fieldname = $Languages['phrase'][$x.'_attr']['fieldname'];
						$version = $Languages['phrase'][$x.'_attr']['pbbversion'];
						$text = $Languages['phrase'][$x];
						$product = $Languages['phrase'][$x.'_attr']['product'];
						$dateline = $Languages['phrase'][$x.'_attr']['date'];
						$username = $Languages['phrase'][$x.'_attr']['username'];
			            $text = str_replace("'", "&#39;", $text);

							$InsertLanguagesArr = array(
								'languageid' => $langid_en,
								'varname' => $varname,
								'fieldname' => $fieldname,
								'text' => $text,
								'dateline' => $dateline,
								'username' => $username,
								'version' => $version,
								'product' => $product
							);
	                    $insertLanguages_en = $db->insert_query("phrase_language", $InsertLanguagesArr);
                     $x += 1;
     			}

           if ($insertLanguages_en)
			{
                  $delLanguages_ar = $db->query("DELETE FROM " . $config['db']['prefix'] . "phrase_language WHERE languageid = '$langid_en' and varname = ''");
			}


		return ($insertLanguages_en) ? true : false;
	}


/**
 * Default configuration
 */
function configure()
{
	global $output, $PBBoard, $errors, $lang;

	$output->print_header($lang->board_config, 'config');

	echo <<<EOF
		<script type="text/javascript">
		function warnUser(inp, warn)
		{
			var parenttr = $('#'+inp.id).closest('tr');
			if(inp.value != inp.defaultValue)
			{
				if(!parenttr.next('.setting_peeker').length)
				{
					var revertlink = ' <a href="javascript:revertSetting(\''+inp.defaultValue+'\', \'#'+inp.id+'\');">{$lang->config_step_revert}</a>';
					parenttr.removeClass('last').after('<tr class="setting_peeker"><td colspan="2">'+warn+revertlink+'</td></tr>');
				}
			} else {
				parenttr.next('.setting_peeker').remove();
				if(parenttr.is(':last-child'))
				{
					parenttr.addClass('last');
				}
			}
		}

		function revertSetting(defval, inpid)
		{
			$(inpid).val(defval);
			var parenttr = $(inpid).closest('tr');
			parenttr.next('.setting_peeker').remove();
			if(parenttr.is(':last-child'))
			{
				parenttr.addClass('last');
			}
		}
		</script>

EOF;

	// If board configuration errors
	if(is_array($errors))
	{
		$error_list = error_list($errors);
		echo $lang->sprintf($lang->config_step_error_config, $error_list);

		$bbname = htmlspecialchars_uni($PBBoard->get_input('bbname'));
		$bburl = htmlspecialchars_uni($PBBoard->get_input('bburl'));
		$websitename = htmlspecialchars_uni($PBBoard->get_input('websitename'));
		$websiteurl = htmlspecialchars_uni($PBBoard->get_input('websiteurl'));
		$cookiedomain = htmlspecialchars_uni($PBBoard->get_input('cookiedomain'));
		$cookiepath = htmlspecialchars_uni($PBBoard->get_input('cookiepath'));
		$contactemail =  htmlspecialchars_uni($PBBoard->get_input('contactemail'));
	}
	else
	{
		$bbname = 'Forums';
		$cookiedomain = '';
		$websitename = 'Your Website';

		$protocol = "http://";
		if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off"))
		{
			$protocol = "https://";
		}

		// Attempt auto-detection
		if($_SERVER['HTTP_HOST'])
		{
			$hostname = $protocol.$_SERVER['HTTP_HOST'];
			$cookiedomain = $_SERVER['HTTP_HOST'];
		}
		elseif($_SERVER['SERVER_NAME'])
		{
			$hostname = $protocol.$_SERVER['SERVER_NAME'];
			$cookiedomain = $_SERVER['SERVER_NAME'];
		}

		if(my_substr($cookiedomain, 0, 4) == "www.")
		{
			$cookiedomain = substr($cookiedomain, 4);
		}

		// IP addresses and hostnames are not valid
		if(my_inet_pton($cookiedomain) !== false || strpos($cookiedomain, '.') === false)
		{
			$cookiedomain = '';
		}
		else
		{
			$cookiedomain = ".{$cookiedomain}";
		}

		if($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 && !preg_match("#:[0-9]#i", $hostname))
		{
			$hostname .= ':'.$_SERVER['SERVER_PORT'];
		}

		$currentlocation = get_current_location('', '', true);
		$noinstall = substr($currentlocation, 0, strrpos($currentlocation, '/install/'));

		$cookiepath = $noinstall.'/';
		$bburl = $hostname.$noinstall;
		$websiteurl = $hostname.'/';
		$contactemail = $_SERVER['SERVER_ADMIN'];
	}

	echo $lang->sprintf($lang->config_step_table, $bbname, $bburl, $websitename, $websiteurl, $cookiedomain, $cookiepath, $contactemail);
	$output->print_footer('adminuser');
}

/**
 * How do we want to name the admin user?
 */
function create_admin_user()
{
	global $output, $PBBoard, $errors, $db, $lang;

	$PBBoard->input['action'] = "adminuser";
	// If no errors then check for errors from last step
	if(!is_array($errors))
	{
		if(empty($PBBoard->input['bburl']))
		{
			$errors[] = $lang->config_step_error_url;
		}
		if(empty($PBBoard->input['bbname']))
		{
			$errors[] = $lang->config_step_error_name;
		}
		if(is_array($errors))
		{
			configure();
		}
	}
	$output->print_header($lang->create_admin, 'admin');

	echo <<<EOF
		<script type="text/javascript">
		function comparePass()
		{
			var parenttr = $('#adminpass2').closest('tr');
			var passval = $('#adminpass2').val();
			if(passval && passval != $('#adminpass').val())
			{
				if(!parenttr.next('.pass_peeker').length)
				{
					parenttr.removeClass('last').after('<tr class="pass_peeker"><td colspan="2">{$lang->admin_step_nomatch}</td></tr>');
				}
			} else {
				parenttr.addClass('last').next('.pass_peeker').remove();
			}
		}
		</script>

EOF;

	if(is_array($errors))
	{
		$error_list = error_list($errors);
        echo $lang->sprintf($lang->admin_step_error_config, $error_list);
		$adminuser = $PBBoard->get_input('adminuser');
		$adminemail = $PBBoard->get_input('adminemail');
	}
	else
	{
		require PBB_ROOT.'includes/config.php';
		$db = db_connection($config);

		$adminuser = $adminemail = '';

		// Insert all the settings

		if(my_substr($PBBoard->get_input('bburl'), -1, 1) == '/')
		{
			$PBBoard->input['bburl'] = my_substr($PBBoard->get_input('bburl'), 0, -1);
		}

       $max_online_date 		    = @date("d/m/Y h:i a");

        $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$db->escape_string($PBBoard->get_input('bbname'))."' WHERE var_name='title'");
        if(!empty($PBBoard->get_input('homename'))){
        $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$db->escape_string($PBBoard->get_input('homename'))."' WHERE var_name='title_portal'");
        }
        $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$db->escape_string($PBBoard->get_input('adminemail'))."' WHERE var_name='send_email'");
        $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$db->escape_string($PBBoard->get_input('adminemail'))."' WHERE var_name='admin_email'");
        $db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$max_online_date."' WHERE var_name='max_online_date'");

		// Save the acp pin
		$bbname = htmlspecialchars_uni($PBBoard->get_input('bbname'));
		$websiteurl = htmlspecialchars_uni($PBBoard->get_input('websiteurl'));
		$websitename = htmlspecialchars_uni($PBBoard->get_input('websitename'));
		$bburl = htmlspecialchars_uni($PBBoard->get_input('bburl'));

		$file = @fopen(PBB_ROOT."includes/settings.php", "a");

		@fwrite($file, "<?php
/**
 * Website Details
 *  Forum URL
 *  Website URL
 *  Website Name
 */

\$setting['forum_url'] = '{$bburl}';
\$setting['website_url'] = '{$websiteurl}';
\$setting['forum_title'] = '{$bbname}';
\$setting['website_name'] = '{$websitename}';");

		@fclose($file);


		echo $lang->admin_step_createadmin;
	}

    echo $lang->sprintf($lang->admin_step_admintable, $adminuser, $adminemail);
	$output->print_footer('final');
}

/**
 * Installation is finished
 */
function install_done()
{
	global $output, $db, $PBBoard, $errors, $cache, $lang, $PowerBB;

	if(empty($PBBoard->input['adminuser']))
	{
		$errors[] = $lang->admin_step_error_nouser;
	}
	if(empty($PBBoard->input['adminpass']))
	{
		$errors[] = $lang->admin_step_error_nopassword;
	}
	if($PBBoard->get_input('adminpass') != $PBBoard->get_input('adminpass2'))
	{
		$errors[] = $lang->admin_step_error_nomatch;
	}
	if(empty($PBBoard->input['adminemail']))
	{
		$errors[] = $lang->admin_step_error_noemail;
	}
	if(is_array($errors))
	{
		create_admin_user();
	}

	require PBB_ROOT.'includes/config.php';
	$db = db_connection($config);


	ob_start();
	$output->print_header($lang->finish_setup, 'finish');

	echo $lang->done_step_usergroupsinserted;
	$now = TIME_NOW;
   	if($_COOKIE['pbb_insall_lang'] == 'ar')
   	{
	$user_title ='مدير المنتدى';
	}
	elseif($_COOKIE['pbb_insall_lang'] == 'en')
    {
	$user_title ='Administrators';
	}

   $username_style_cache	=	'<strong><em><span style="color: #800000;">' . $db->escape_string($PBBoard->get_input('adminuser')) . '</span></em></strong>';

	$saltedpw = md5($PBBoard->get_input('adminpass'));

	$newuser = array(
		'id' => '1',
		'username' => $db->escape_string($PBBoard->get_input('adminuser')),
		'password' => $saltedpw,
		'email' => $db->escape_string($PBBoard->get_input('adminemail')),
		'usergroup' => '1', // assigned above
		'user_gender' => 'm',
		'register_date' => $now,
		'register_time' => $now,
		'style' => '1',
		'username_style_cache' => $username_style_cache,
		'user_title' => $user_title,
		'lang' => '1',
		'user_notes' => 'My note',
		'user_sig' => '',
		'user_info' => '',
		'avater_path' => '',
		'away_msg' => '',
		'style_cache' => '1',
		'subject_sig' => '',
		'style_sheet_profile' => '',
		'autoreply_msg' => '',
		'reply_sig' => '',
		'style_id_cache' => '1',
		'inviter' => $db->escape_string($PBBoard->get_input('adminuser')),
		'visitor' => '1'
	);

	$db->insert_query('member', $newuser);
	echo $lang->done . '</p>';

	echo $lang->done_step_adminoptions;

	echo $lang->done . '</p>';

$db->query("UPDATE " . $config['db']['prefix'] . "info SET value='".$now."' WHERE var_name='create_date'");

echo $lang->done_step_success;

    $update = $db->query("UPDATE " . $config['db']['prefix'] . "subject SET writer='".$db->escape_string($PBBoard->get_input('adminuser'))."' WHERE id='1'");
    $update = $db->query("UPDATE " . $config['db']['prefix'] . "section SET last_writer='".$db->escape_string($PBBoard->get_input('adminuser'))."' WHERE id='1'");
    $update = $db->query("UPDATE " . $config['db']['prefix'] . "section SET last_writer='".$db->escape_string($PBBoard->get_input('adminuser'))."' WHERE id='2'");

$forums =  upgrade_update_section_cache();
 if ($forums)
 {   echo $lang->finish_upgrade_sections_cache;
 }
	$written = 0;
	if(is_writable('./'))
	{
		$lock = @fopen('./lock', 'w');
		$written = @fwrite($lock, '1');
		@fclose($lock);
		if($written)
		{
			echo $lang->done_step_locked;
		}
	}
	if(!$written)
	{
       echo $lang->done_step_dirdelete;
	}

	//echo $lang->done_whats_next;
	$output->print_footer('');
}

/**
*Update Section Cache ;)
*/
function upgrade_update_section_cache()
{
	global $db, $output ,$config, $lang, $PowerBB;

     $SecListquery = $db->query("SELECT * FROM ".$config['db']['prefix']."section");
      while ($Section = $db->fetch_array($SecListquery))
		{
		     echo $lang->update_section_cache." " .$Section['title']." ".$lang->done."<br />";

		   if ($Section['parent'] == 0)
 			{
				$Section_parent 	= 	$Section['id'];
				$CacheArrquery = $db->query("SELECT * FROM ".$config['db']['prefix']."section WHERE parent = '$Section_parent'");
				$CacheArr = $db->fetch_array($CacheArrquery);
				$arrparent = $CacheArr['parent'];
				$arr = $db->query("SELECT * FROM ".$config['db']['prefix']."section WHERE parent = '$arrparent' ORDER by sort ASC");
				$cache = array();
				$x = 0;
	 			while ($forums = $db->fetch_array($arr))
	 			{
                       $cache[$x] 							= 	array();
					$cache[$x]['id'] 					= 	$forums['id'];
					$cache[$x]['title'] 				= 	$forums['title'];
					$cache[$x]['section_describe'] 		= 	$forums['section_describe'];
					$cache[$x]['parent'] 				= 	$forums['parent'];
					$cache[$x]['sort'] 					= 	$forums['sort'];
					$cache[$x]['section_picture'] 		= 	$forums['section_picture'];
					$cache[$x]['sectionpicture_type'] 	= 	$forums['sectionpicture_type'];
					$cache[$x]['use_section_picture'] 	= 	$forums['use_section_picture'];
					$cache[$x]['linksection'] 			= 	$forums['linksection'];
					$cache[$x]['linkvisitor'] 			= 	$forums['linkvisitor'];
					$cache[$x]['last_writer'] 			= 	$forums['last_writer'];

					$last_writer 			= 	$forums['last_writer'];
	                $MemberArr = $db->query("SELECT * FROM ".$config['db']['prefix']."member WHERE username = '$last_writer'");
	                $rows = $db->fetch_array($MemberArr);

					$cache[$x]['last_writer_id'] 	    = 	$rows['id'];
					$cache[$x]['avater_path'] 		    = 	$rows['avater_path'];
					$cache[$x]['username_style_cache']  = 	$rows['username_style_cache'];

					$cache[$x]['last_subject'] 			= 	$forums['last_subject'];
					$cache[$x]['last_subjectid'] 		= 	$forums['last_subjectid'];
					$cache[$x]['last_date'] 			= 	$forums['last_date'];
					$cache[$x]['last_time'] 			= 	$forums['last_time'];
					$cache[$x]['subject_num'] 			= 	$forums['subject_num'];
					$cache[$x]['reply_num'] 			= 	$forums['reply_num'];
					$cache[$x]['moderators'] 			= 	$forums['moderators'];
					$cache[$x]['icon'] 	        		=  	$forums['icon'];
					$cache[$x]['hide_subject'] 	        =  	$forums['hide_subject'];
					$cache[$x]['sec_section'] 	        =  	$forums['sec_section'];
					$cache[$x]['section_password'] 	    =  	$forums['section_password'];
					$cache[$x]['last_berpage_nm'] 	    =  	$forums['last_berpage_nm'];
					$cache[$x]['last_reply'] 	        =  	$forums['last_reply'];
					$cache[$x]['forums_cache'] 			= 	$forums['forums_cache'];
					$cache[$x]['forum_title_color']     = 	$forums['forum_title_color'];
					$cache[$x]['review_subject']        = 	$forums['review_subject'];
					$cache[$x]['replys_review_num']     = 	$forums['replys_review_num'];
					$cache[$x]['subjects_review_num']   = 	$forums['subjects_review_num'];
					$cache[$x]['groups'] 				= 	array();

	 				$section_id 					= 	$forums['id'];
	                $GroupArr = $db->query("SELECT * FROM ".$config['db']['prefix']."sectiongroup WHERE section_id = '$section_id' ORDER by id ASC");

	 				$last_subjectid 					= 	$forums['last_subjectid'];
	                $prefixArr = $db->query("SELECT * FROM ".$config['db']['prefix']."subject WHERE id = '$last_subjectid' ORDER by id ASC");
	                $rows = $db->fetch_array($prefixArr);

					$cache[$x]['prefix_subject']   = 	$rows['prefix_subject'];
					while ($group = $db->fetch_array($GroupArr))
					{				        $cache[$x]['groups'][$group['group_id']] 					=	array();
						$cache[$x]['groups'][$group['group_id']]['view_section'] 	= 	$group['view_section'];
						$cache[$x]['groups'][$group['group_id']]['main_section'] 	= 	$group['main_section'];
					}
					$x += 1;
	 			}

				$cache = base64_encode(json_encode($cache));

				if ($cache)
				{
					$file_forums_cache = PBB_ROOT."cache/forums_cache/forums_cache_".$Section['id'].".php";
					$fp = fopen($file_forums_cache,'w');
					if (!$fp)
					{
					return 'ERROR::CAN_NOT_OPEN_THE_FILE';
					}
					$Ds = '$';
					$Section = $Section['id'];
					$forums_cache = "<?php \n".$Ds."forums_cache ='".$cache."';\n ?> ";

					$fw = fwrite($fp,$forums_cache);
					fclose($fp);
				}

 			}

 		}


}

/**
 * @param array $config
 *
 * @return DB_MySQL|DB_MySQLi
 */
function db_connection($config)
{
	require PBB_ROOT.'includes/config.php';

	require_once PBB_ROOT."includes/db_".$config['db']['dbtype'].".php";
	switch($config['db']['dbtype'])
	{
		case "mysqli":
			$db = new DB_MySQLi;
			break;
		default:
			$db = new DB_MySQL;
	}


	$connect_array = array(
		"hostname" => $config['db']['server'],
		"username" => $config['db']['username'],
		"password" => $config['db']['password'] ,
		"database" => $config['db']['name'],
		"encoding" => $config['db']['encoding']
	);



	// Connect to Database
	define('TABLE_PREFIX', $config['db']['prefix']);
	$db->connect($connect_array);
	$db->set_table_prefix(TABLE_PREFIX);
	$db->type = $config['db']['dbtype'];

	return $db;
}

/**
 * @param array $array
 *
 * @return string
 */
function error_list($array)
{
	$string = "<ul>\n";
	foreach($array as $error)
	{
		$string .= "<li>{$error}</li>\n";
	}
	$string .= "</ul>\n";
	return $string;
}

/**
 * Custom chmod function to fix problems with hosts who's server configurations screw up umasks
 *
 * @param string $file The file to chmod
 * @param string $mode The mode to chmod(i.e. 0666)
 * @return bool
 */
function my_chmod($file, $mode)
{
	// Passing $mode as an octal number causes strlen and substr to return incorrect values. Instead pass as a string
	if(substr($mode, 0, 1) != '0' || strlen($mode) !== 4)
	{
		return false;
	}
	$old_umask = umask(0);

	// We convert the octal string to a decimal number because passing a octal string doesn't work with chmod
	// and type casting subsequently removes the prepended 0 which is needed for octal numbers
	$result = chmod($file, octdec($mode));
	umask($old_umask);
	return $result;
}


/**
 * Write our settings to the settings file
 */
function write_settings()
{
	global $db, $lang;

	$settings = '';
	$query = $db->simple_select('settings', '*', '', array('order_by' => 'title'));
	while($setting = $db->fetch_array($query))
	{
		$setting['value'] = str_replace("\"", "\\\"", $setting['value']);
		$settings .= "\$settings['{$setting['name']}'] = \"{$setting['value']}\";\n";
	}
	if(!empty($settings))
	{
		$settings = "<?php\n/*********************************\ \n  DO NOT EDIT THIS FILE, PLEASE USE\n  THE SETTINGS EDITOR\n\*********************************/\n\n{$settings}\n";
		$file = fopen(PBB_ROOT."includes/settings.php", "w");
		fwrite($file, $settings);
		fclose($file);
	}
}

function xml_array($contents, $get_attributes=1, $priority = 'tag')
 {
	 global $PBBoard;

    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();

        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;

                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }

    return($xml_array);
 }

 /**
 * Cuts a string at a specified point, mb strings accounted for
 *
 * @param string $string The string to cut.
 * @param int $start Where to cut
 * @param int $length (optional) How much to cut
 * @param bool $handle_entities (optional) Properly handle HTML entities?
 * @return string The cut part of the string.
 */
function my_substr($string, $start, $length=null, $handle_entities = false)
{
	if($handle_entities)
	{
		$string = unhtmlentities($string);
	}
	if(function_exists("mb_substr"))
	{
		if($length != null)
		{
			$cut_string = mb_substr($string, $start, $length);
		}
		else
		{
			$cut_string = mb_substr($string, $start);
		}
	}
	else
	{
		if($length != null)
		{
			$cut_string = substr($string, $start, $length);
		}
		else
		{
			$cut_string = substr($string, $start);
		}
	}

	if($handle_entities)
	{
		$cut_string = htmlspecialchars_uni($cut_string);
	}
	return $cut_string;
}

/**
 * Returns any html entities to their original character
 *
 * @param string $string The string to un-htmlentitize.
 * @return string The un-htmlentitied' string.
 */
function unhtmlentities($string)
{
	// Replace numeric entities
	$string = preg_replace_callback('~&#x([0-9a-f]+);~i', create_function('$matches', 'return unichr(hexdec($matches[1]));'), $string);
	$string = preg_replace_callback('~&#([0-9]+);~', create_function('$matches', 'return unichr($matches[1]);'), $string);

	// Replace literal entities
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);

	return strtr($string, $trans_tbl);
}

/**
*Update Section Cache ;)
*/
function UpdateSectionCache($SectionCache)
{
global $PowerBB;

    $update = $db->query("UPDATE " . $config['db']['prefix'] . "section SET last_writer='".$db->escape_string($PBBoard->get_input('adminuser'))."' WHERE id='2'");

// The number of section's replys number
$reply_num = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE section = '$SectionCache' "));
$UpdateArr 					= 	array();
$UpdateArr['field']			=	array();
$UpdateArr['field']['reply_num'] 	= 	$reply_num;
$UpdateArr['where']					= 	array('id',$SectionCache);
$UpdateReplyNumber = $PowerBB->core->Update($UpdateArr,'section');
// The number of section's subjects number
$subject_nm = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' AND delete_topic<>1"));
// The number of section's subjects number
$UpdateArr 					= 	array();
$UpdateArr['field']			=	array();
$UpdateArr['field']['subject_num'] 	= 	$subject_nm;
$UpdateArr['where']					= 	array('id',$SectionCache);
$UpdateSubjectNumber = $PowerBB->core->Update($UpdateArr,'section');
$GetLastqueryReplyForm = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['reply'] . " WHERE section = '$SectionCache' AND delete_topic<>1 AND review_reply<>1 ORDER by write_time DESC LIMIT 0 , 30");
$GetLastReplyForm = $PowerBB->DB->sql_fetch_array($GetLastqueryReplyForm);
$GetLastSubjectInfoQuery = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE section = '$SectionCache' AND delete_topic<>1 AND review_subject<>1 ORDER by native_write_time DESC LIMIT 0 , 30 ");
$GetLastSubjectInf = $PowerBB->DB->sql_fetch_array($GetLastSubjectInfoQuery);
if ($PowerBB->_GET['page'] != 'new_topic')
{
if($GetLastReplyForm['write_time'] > $GetLastSubjectInf['native_write_time'])
{
$GetSubjectInfoQuery = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['subject'] . " WHERE id = '".$GetLastReplyForm['subject_id']."' AND delete_topic<>1 AND review_subject<>1 ");
$SubjectInf = $PowerBB->DB->sql_fetch_array($GetSubjectInfoQuery);
// Get info subject
$last_subjectid = $GetLastReplyForm['subject_id'];
$icon = $SubjectInf['icon'];
$last_reply = $SubjectInf['last_reply'];
$last_berpage_nm = $SubjectInf['last_berpage_nm'];
$last_writer = $SubjectInf['last_replier'];
$title = $SubjectInf['title'];
$last_date = $SubjectInf['write_time'];
}
else
{
// Get info subject
$last_subjectid = $GetLastSubjectInf['id'];
$icon = $GetLastSubjectInf['icon'];
$last_reply = $GetLastSubjectInf['last_reply'];
$last_berpage_nm = $GetLastSubjectInf['last_berpage_nm'];
$last_writer = $GetLastSubjectInf['writer'];
$title = $GetLastSubjectInf['title'];
$last_date = $GetLastSubjectInf['write_time'];
}
}
if ($PowerBB->_GET['page'] == 'new_topic')
{
// Get info subject
$last_subjectid = $GetLastSubjectInf['id'];
$icon = $GetLastSubjectInf['icon'];
$last_reply = $GetLastSubjectInf['last_reply'];
$last_berpage_nm = $GetLastSubjectInf['last_berpage_nm'];
$last_writer = $GetLastSubjectInf['writer'];
$title = $GetLastSubjectInf['title'];
$last_date = $GetLastSubjectInf['native_write_time'];
}
// Get Section Info
$SecArr 			= 	array();
$SecArr['where'] 	= 	array('id',$SectionCache);
$sdr_SectionInfo = $PowerBB->core->GetInfo($SecArr,'section');

if ($subject_nm == 0)
{
// Get Section Info
$SecParenreplytArr = $PowerBB->DB->sql_query("SELECT * FROM " . $PowerBB->table['section'] . " WHERE parent='$SectionCache' AND review_subject<>1 ORDER by last_time DESC LIMIT 0 , 30 ");
$sdr_ParentsInfo = $PowerBB->DB->sql_fetch_array($SecParenreplytArr);
if($sdr_ParentsInfo['last_writer'] !='')
{
// Update Last subject's information in Section Form
$UpdateLastFormSecArr = array();
$UpdateLastFormSecArr['field']			=	array();
$UpdateLastFormSecArr['field']['last_writer'] 		= 	$sdr_ParentsInfo['last_writer'];
$UpdateLastFormSecArr['field']['last_subject'] 		= 	$sdr_ParentsInfo['last_subject'];
$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$sdr_ParentsInfo['last_subjectid'];
$UpdateLastFormSecArr['field']['last_date'] 	= 	$sdr_ParentsInfo['last_date'];
$UpdateLastFormSecArr['field']['last_time'] 	= 	$sdr_ParentsInfo['last_time'];
$UpdateLastFormSecArr['field']['icon'] 		    = 	$sdr_ParentsInfo['icon'];
$UpdateLastFormSecArr['field']['moderators'] 		    = 	$cache;
$UpdateLastFormSecArr['field']['last_reply'] 	= 	$sdr_ParentsInfo['last_reply'];
$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	$sdr_ParentsInfo['last_berpage_nm'];
$UpdateLastFormSecArr['field']['replys_review_num']  = 	$sdr_ParentsInfo['replys_review_num'];
$UpdateLastFormSecArr['field']['subjects_review_num']  = 	$sdr_ParentsInfo['subjects_review_num'];
$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);
// Update Last Form Sec subject's information
$UpdateLastFormSec = $PowerBB->core->Update($UpdateLastFormSecArr,'section');
}
else
{
// Update Last subject's information in Section Form
$UpdateLastFormSecArr = array();
$UpdateLastFormSecArr['field']			=	array();
$UpdateLastFormSecArr['field']['last_writer'] 		= 	'';
$UpdateLastFormSecArr['field']['last_subject'] 		= 	'';
$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	'';
$UpdateLastFormSecArr['field']['last_date'] 	= 	'';
$UpdateLastFormSecArr['field']['last_time'] 	= 	'';
$UpdateLastFormSecArr['field']['icon'] 		    = 	'';
$UpdateLastFormSecArr['field']['last_reply'] 	= 	0;
$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	0;
$UpdateLastFormSecArr['field']['replys_review_num']  = 	0;
$UpdateLastFormSecArr['field']['subjects_review_num']  = 	0;
$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);
// Update Last Form Sec subject's information
$UpdateLastFormSec = $PowerBB->core->Update($UpdateLastFormSecArr,'section');
}
}
else
{
$review_replyNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['reply'] . " WHERE section='$SectionCache' and review_reply=1 "));
$review_subjectNumArr = $PowerBB->DB->sql_num_rows($PowerBB->DB->sql_query("SELECT id FROM " . $PowerBB->table['subject'] . " WHERE section='$SectionCache' and review_subject=1 "));

// Update Last subject's information in Section Form
$UpdateLastFormSecArr = array();
$UpdateLastFormSecArr['field']			=	array();
$UpdateLastFormSecArr['field']['last_writer'] 		= 	$last_writer;
$UpdateLastFormSecArr['field']['last_subject'] 		= 	$title;
$UpdateLastFormSecArr['field']['last_subjectid'] 	= 	$last_subjectid;
$UpdateLastFormSecArr['field']['last_date'] 	= 	$last_date;
$UpdateLastFormSecArr['field']['last_time'] 	= 	$last_date;
$UpdateLastFormSecArr['field']['icon'] 		    = 	$icon;
$UpdateLastFormSecArr['field']['moderators'] 		    = 	$cache;
$UpdateLastFormSecArr['field']['last_reply'] 	= 	$last_reply;
$UpdateLastFormSecArr['field']['last_berpage_nm']  = 	$last_berpage_nm;
$UpdateLastFormSecArr['field']['replys_review_num']  = 	$review_replyNumArr;
$UpdateLastFormSecArr['field']['subjects_review_num']  = 	$review_subjectNumArr;
$UpdateLastFormSecArr['where'] 		        = 	array('id',$SectionCache);
// Update Last Form Sec subject's information
$UpdateLastFormSec = $PowerBB->core->Update($UpdateLastFormSecArr,'section');
}

return;
}



?>
