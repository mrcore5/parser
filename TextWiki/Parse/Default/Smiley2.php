<?php

class Text_Wiki_Parse_Default_Smiley2 extends Text_Wiki_Parse
{
	// These are the mrcore default but you CAN overwrite them and append to them
	// Just specify a <phpw> tag in your global mrcore topic and global $global_smileys and $global_smileys = array(':)' => array ('smile', 'Smile', '(:'))...
	var $conf = [
		'smileys' => [
			':D'             => ['biggrin', 'Very Happy', ':grin:'],
			':)'             => ['smile', 'Smile', '(:'],
			':('             => ['sad', 'Sad', '):'],
			':o'             => ['surprised', 'Surprised', ':eek:', 'o:'],
			':shock:'        => ['eek', 'Shocked'],
			':?'             => ['confused', 'Confused'],
			'8)'             => ['cool', 'Cool', '(8'],
			':lol:'          => ['lol', 'Laughing'],
			':x'             => ['mad', 'Mad'],
			':P'             => ['razz', 'Razz'],
			':oops:'         => ['redface', 'Embarassed'],
			':cry:'          => ['cry', 'Crying or Very sad'],
			':evil:'         => ['evil', 'Evil or Very Mad'],
			':twisted:'      => ['twisted', 'Twisted Evil'],
			':roll:'         => ['rolleyes', 'Rolling Eyes'],
			';)'             => ['wink', 'Wink', '(;'],
			':|'             => ['neutral', 'Neutral', '|:'],
			':mrgreen:'      => ['mrgreen', 'Mr. Green'],

			':new:'          => ['new',' New'],
			':new2:'         => ['new2',' New'],
			':new3:'         => ['new3',' New'],
			':new4:'         => ['new4',' New'],
			':new5:'         => ['new5',' New'],
			':hnew5:'        => ['new5_h',' New'],
			':new6:'         => ['new6',' New'],
			':hnew6:'        => ['new6_h',' New'],
			':new7:'         => ['new7',' New'],
			':hnew7:'        => ['new7_h',' New'],
			':new8:'         => ['new8',' New'],
			':hnew8:'        => ['new8_h',' New'],
			':new9:'         => ['new9',' New'],
			':new10:'        => ['new10',' New'],
			':new11:'        => ['new11',' New'],
			':new12:'        => ['new12',' New'],
			':hnew12:'       => ['new12_h',' New'],

			':u:'            => ['up', 'Up'],
			':d:'            => ['down', 'Down'],
			':l:'            => ['left', 'Left'],
			':r:'            => ['right', 'Right'],
			':u2:'           => ['u2', 'Up'],
			':d2:'           => ['d2', 'Down'],
			':l2:'           => ['l2', 'Left'],
			':r2:'           => ['r2', 'Right'],
			':u3:'           => ['u3', 'Up'],
			':d3:'           => ['d3', 'Down'],
			':l3:'           => ['l3', 'Left'],
			':r3:'           => ['r3', 'Right'],
			':u4:'           => ['u4', 'Up'],
			':d4:'           => ['d4', 'Down'],
			':l4:'           => ['l4', 'Left'],
			':r4:'           => ['r4', 'Right'],

			':!:'            => ['exclaim', 'Exclamation1', ':exclaim:'],
			':h!:'           => ['exclaim_h', 'Exclamation1', ':hexclaim:'],
			':!2:'           => ['exclaim2', 'Exclamation2', ':exclaim2:', ':!!:'],
			':h!2:'          => ['exclaim2_h', 'Exclamation2', ':hexclaim2:', ':h!!:'],
			':!3:'           => ['exclaim3', 'Exclamation3', ':exclaim3:', ':!!!:'],
			':h!3:'          => ['exclaim3_h', 'Exclamation3', ':hexclaim3:', ':h!!!:'],
			':!4:'           => ['exclaim4', 'Exclamation4', ':exclaim4:', ':!!!!:'],
			':h!4:'          => ['exclaim4_h', 'Exclamation4', ':hexclaim4:', ':h!!!!:'],
			':!5:'           => ['exclaim5', 'Exclamation4', ':exclaim5:', ':!!!!!:'],
			':h!5:'          => ['exclaim5_h', 'Exclamation4', ':hexclaim5:', ':h!!!!!:'],
			':!6:'           => ['exclaim6', 'Exclamation4', ':exclaim6:', ':!!!!!!:'],
			':h!6:'          => ['exclaim6_h', 'Exclamation4', ':hexclaim6:', ':h!!!!!!:'],

			':?:'            => ['question', 'Question', ':question:'],
			':h?:'           => ['question_h', 'Question', ':hquestion:'],
			':?2:'           => ['question2', 'Question', ':question2:', ':??:'],
			':h?2:'          => ['question2_h', 'Question', ':hquestion2:', ':h??:'],
			':?3:'           => ['question3', 'Question', ':question3:', ':???:'],
			':h?3:'          => ['question3_h', 'Question', ':hquestion3:', ':h???:'],
			':?4:'           => ['question4', 'Question', ':question4:', ':????:'],
			':h?4:'          => ['question4_h', 'Question', ':hquestion4:', ':h????:'],
			':?5:'           => ['question5', 'Question', ':question5:', ':?????:'],
			':h?5:'          => ['question5_h', 'Question', ':hquestion5:', ':h?????:'],

			':i:'            => ['idea', 'Idea', ':idea:'],
			':hi:'           => ['idea_h', 'Idea', ':hidea:'],
			':i2:'           => ['idea2', 'Idea', ':idea2:', ':ii:'],
			':hi2:'          => ['idea2_h', 'Idea', ':hidea2:', ':hii:'],
			':i3:'           => ['idea3', 'Idea', ':idea3:', ':iii:'],
			':hi3:'          => ['idea3_h', 'Idea', ':hidea3:', ':hiii:'],

			':fixme:'        => ['fixme', 'Fixme'],
			':hfixme:'       => ['fixme_h', 'Fixme'],
			':fixme2:'       => ['fixme2', 'Fixme'],
			':hfixme2:'      => ['fixme2_h', 'Fixme'],
			':fixme3:'       => ['fixme3', 'Fixme'],
			':hfixme3:'      => ['fixme3_h', 'Fixme'],

			':toolbox:'      => ['toolbox', 'Toolbox'],
			':htoolbox:'     => ['toolbox_h', 'Toolbox'],
			':tool:'         => ['tool', 'Tool', ':tools:'],
			':htool:'        => ['tool_h', 'Tool', ':htools:'],
			':tool2:'        => ['tool2', 'Tool', ':tools2:'],
			':htool2:'       => ['tool2_h', 'Tool', ':htools2:'],
			':tool3:'        => ['tool3', 'Tool', ':tools3:'],
			':htool3:'       => ['tool3_h', 'Tool', ':htools3:'],
			':tool4:'        => ['tool4', 'Tool', ':tools4:'],
			':htool4:'       => ['tool4_h', 'Tool', ':htools4:'],

			':download:'     => ['download', 'Download', ':downloads:'],
			':hdownload:'    => ['download_h', 'Download', ':hdownloads:'],
			':download2:'    => ['download2', 'Download', ':downloads2:'],
			':hdownload2:'   => ['download2_h', 'Download', ':hdownloads2:'],
			':download3:'    => ['download3', 'Download', ':downloads3:'],
			':hdownload3:'   => ['download3_h', 'Download', ':hdownloads3:'],
			':download4:'    => ['download4', 'Download', ':downloads4:'],
			':hdownload4:'   => ['download4_h', 'Download', ':hdownloads4:'],
			':download5:'    => ['download5', 'Download', ':downloads5:'],
			':hdownload5:'   => ['download5_h', 'Download', ':hdownloads5:'],
			':download6:'    => ['download6', 'Download', ':downloads6:'],
			':hdownload6:'   => ['download6_h', 'Download', ':hdownloads6:'],
			':download7:'    => ['download7', 'Download', ':downloads7:'],
			':hdownload7:'   => ['download7_h', 'Download', ':hdownloads7:'],

			':upload:'       => ['upload', 'Upload', ':uploads:'],
			':hupload:'      => ['upload_h', 'Upload', ':huploads:'],
			':upload2:'      => ['upload2', 'Upload', ':uploads2:'],
			':hupload2:'     => ['upload2_h', 'Upload', ':huploads2:'],
			':upload3:'      => ['upload3', 'Upload', ':uploads2:'],
			':hupload3:'     => ['upload3_h', 'Upload', ':huploads2:'],
			':upload4:'      => ['upload4', 'Upload', ':uploads2:'],
			':hupload4:'     => ['upload4_h', 'Upload', ':huploads2:'],
			':upload5:'      => ['upload5', 'Upload', ':uploads2:'],
			':hupload5:'     => ['upload5_h', 'Upload', ':huploads2:'],
			':upload6:'      => ['upload6', 'Upload', ':uploads2:'],
			':hupload6:'     => ['upload6_h', 'Upload', ':huploads2:'],
			':upload7:'      => ['upload7', 'Upload', ':uploads2:'],
			':hupload7:'     => ['upload7_h', 'Upload', ':huploads2:'],

			':plan:'         => ['plan', 'Plan', ':plans:', ':blueprint:', ':blueprints:'],
			':hplan:'        => ['plan_h', 'Plan', ':hplans:', ':hblueprint:', ':hblueprints:'],
			':plan2:'        => ['plan2', 'Plan', ':plans2:', ':blueprint2:', ':blueprints2:'],
			':hplan2:'       => ['plan2_h', 'Plan', ':hplans2:', ':hblueprint2:', ':hblueprints2:'],

			':clipboard:'    => ['clipboard', 'Clipboard'],
			':hclipboard:'   => ['clipboard_h', 'Clipboard'],

			':comment:'      => ['comment', 'Comment', ':comments:'],
			':hcomment:'     => ['comment_h', 'Comment', ':hcomments:'],
			':comment2:'     => ['comment2', 'Comment', ':comments2:'],
			':hcomment2:'    => ['comment2_h', 'Comment', ':hcomments2:'],
			':comment3:'     => ['comment3', 'Comment', ':comments3:'],
			':hcomment3:'    => ['comment3_h', 'Comment', ':hcomments3:'],
			':comment4:'     => ['comment4', 'Comment', ':comments4:'],
			':hcomment4:'    => ['comment4_h', 'Comment', ':hcomments4:'],
			':comment5:'     => ['comment5', 'Comment', ':comments5:'],
			':hcomment5:'    => ['comment5_h', 'Comment', ':hcomments5:'],

			':construction:'  => ['construction', 'Construction'],
			':hconstruction:' => ['construction_h', 'Construction'],
			':construction2:' => ['construction2', 'Construction'],
			':hconstruction2:'=> ['construction2_h', 'Construction'],
			':construction3:' => ['construction3', 'Construction'],
			':hconstruction3:'=> ['construction3_h', 'Construction'],

			':error:'        => ['error', 'Error', ':errors:'],
			':herror:'       => ['error_h', 'Error', ':herrors:'],
			':error2:'       => ['error2', 'Error', ':errors2:'],
			':herror2:'      => ['error2_h', 'Error', ':herrors2:'],
			':error3:'       => ['error3', 'Error', ':errors3:'],
			':herror3:'      => ['error3_h', 'Error', ':herrors3:'],
			':error4:'       => ['error4', 'Error', ':errors4:'],
			':herror4:'      => ['error4_h', 'Error', ':herrors4:'],

			':graph:'        => ['graph', 'Graph'],
			':hgraph:'       => ['graph_h', 'Graph'],
			':graph2:'       => ['graph2', 'Graph'],
			':hgraph2:'      => ['graph2_h', 'Graph'],

			':help:'         => ['help', 'Help'],
			':hhelp:'        => ['help_h', 'Help'],
			':help2:'        => ['help2', 'Help'],
			':hhelp2:'       => ['help2_h', 'Help'],

			':info:'         => ['info', 'Info', ':summary:'],
			':hinfo:'        => ['info_h', 'Info', ':hsummary:'],
			':info2:'        => ['info2', 'Info', ':summary2:'],
			':hinfo2:'       => ['info2_h', 'Info', ':hsummary2:'],

			':search:'       => ['search', 'Search'],
			':hsearch:'      => ['search_h', 'Search'],

			':config:'       => ['config', 'Config', ':settings:', ':setting:'],
			':hconfig:'      => ['config_h', 'Config', ':hsettings:', ':hsetting:'],
			':config2:'      => ['config2', 'Config', ':settings2:', ':setting2:'],
			':hconfig2:'     => ['config2_h', 'Config', ':hsettings2:', ':hsetting2:'],
			':config3:'      => ['config3', 'Config', ':settings3:', ':setting3:'],
			':hconfig3:'     => ['config3_h', 'Config', ':hsettings3:', ':hsetting3:'],
			':config4:'      => ['config4', 'Config', ':settings4:', ':setting4:'],
			':hconfig4:'     => ['config4_h', 'Config', ':hsettings4:', ':hsetting4:'],


			':stop:'         => ['stop', 'Stop'],
			':hstop:'        => ['stop_h', 'Stop'],
			':stop2:'        => ['stop2', 'Stop'],
			':hstop2:'       => ['stop2_h', 'Stop'],
			':stop3:'        => ['stop3', 'Stop'],
			':hstop3:'       => ['stop3_h', 'Stop'],

			':add:'          => ['add', 'Add', ':plus:'],
			':hadd:'         => ['add_h', 'Add', ':hplus:'],
			':subtract:'     => ['subtract', 'Subtract', ':minus:'],
			':hsubtract:'    => ['subtract_h', 'Subtract', ':hminus:'],
			':check:'        => ['check', 'Check'],
			':hcheck:'       => ['check_h', 'Check'],

			':thumbdown:'    => ['thumbdown', 'Thumb Down', ':thumbsup:'],
			':thumbup:'      => ['thumbup', 'Thumb Up', ':thumbsdown'],
			':hthumbdown:'   => ['thumbdown_h', 'Thumb Down', ':hthumbsup:'],
			':hthumbup:'     => ['thumbup_h', 'Thumb Up', ':hthumbsdown'],
			':doc:'          => ['doc', 'Document', ':docs:', ':document:', ':documents:'],
			':hdoc:'         => ['doc_h', 'Document', ':hdocs:', ':hdocument:', ':hdocuments:'],

			':link:'         => ['link', 'Link', ':links:'],
			':hlink:'        => ['link_h', 'Link', ':hlinks:'],
			':link2:'        => ['link2', 'Link', ':links2:'],
			':hlink2:'       => ['link2_h', 'Link', ':hlinks2:'],
			':link3:'        => ['link3', 'Link', ':links3:'],
			':hlink3:'       => ['link3_h', 'Link', ':hlinks3:'],
			':link4:'        => ['link4', 'Link', ':links4:'],
			':hlink4:'       => ['link4_h', 'Link', ':hlinks4:'],
			':link5:'        => ['link5', 'Link', ':links5:'],
			':hlink5:'       => ['link5_h', 'Link', ':hlinks5:'],

			':book:'         => ['book', 'Book', ':books:', ':read:', ':reading:', ':readme:'],
			':hbook:'        => ['book_h', 'Book', ':hbooks:', ':hread:', ':hreading:', ':hreadme:'],
			':anchor:'       => ['anchor', 'Anchor', ':anchors:'],
			':hanchor:'      => ['anchor_h', 'Anchor', ':hanchors:'],

			':note:'         => ['note', 'Postit', ':postits:', ':sticky:', ':stickies:', ':postit:', ':notes:'],
			':hnote:'        => ['note_h', 'Postit', ':hpostits:', ':hsticky:', ':hstickies:', ':hpostit:', ':hnotes:'],
			':note2:'        => ['note2', 'Postit', ':postits2:', ':sticky2:', ':stickies2:', ':postit2:', ':notes2:'],
			':hnote2:'       => ['note2_h', 'Postit', ':hpostits2:', ':hsticky2:', ':hstickies2:', ':hpostit2:', ':hnotes2:'],
			':note3:'        => ['note3', 'Postit', ':postits3:', ':sticky3:', ':stickies3:', ':postit3:', ':notes3:'],
			':hnote3:'       => ['note3_h', 'Postit', ':hpostits3:', ':hsticky3:', ':hstickies3:', ':hpostit3:', ':hnotes3:'],

			':map:'          => ['map', 'Map', ':maps:', ':goal:', ':goals:'],
			':hmap:'         => ['map_h', 'Map', ':maps:', ':hgoal:', ':hgoals:'],
			':map2:'         => ['map2', 'Map', ':maps2:', ':goal2:', ':goals2:'],
			':hmap2:'        => ['map2_h', 'Map', ':maps2:', ':hgoal2:', ':hgoals2:'],

			':split:'        => ['split', 'Split'],
			':hsplit:'       => ['split_h', 'Split'],
			':join:'         => ['join', 'Join'],
			':hjoin:'        => ['join_h', 'Join'],
			':refresh:'      => ['refresh', 'Refresh'],
			':hrefresh:'     => ['refresh_h', 'Refresh'],
			':refresh2:'     => ['refresh2', 'Refresh'],
			':hrefresh2:'    => ['refresh2_h', 'Refresh'],
			':refresh3:'     => ['refresh3', 'Refresh'],
			':hrefresh3:'    => ['refresh3_h', 'Refresh'],
			':undo:'         => ['undo', 'Undo'],
			':hundo:'        => ['undo_h', 'Undo'],
			':redo:'         => ['redo', 'Redo'],
			':hredo:'        => ['redo_h', 'Redo'],

			':resource:'     => ['resource', 'Resource', ':resources:', ':reference:', ':references:'],
			':hresource:'    => ['resource_h', 'Resource', ':hresources:', ':hreference:', ':hreferences:'],
			':resource2:'    => ['resource2', 'Resource', ':resources2:', ':reference2:', ':references2:'],
			':hresource2:'   => ['resource2_h', 'Resource', ':hresources2:', ':hreference2:', ':hreferences2:'],

			':shell:'        => ['shell', 'Shell'],
			':hshell:'       => ['shell_h', 'Shell'],
			':shell2:'       => ['shell2', 'Shell'],
			':hshell2:'      => ['shell2_h', 'Shell'],
			':shell3:'       => ['shell3', 'Shell'],
			':hshell3:'      => ['shell3_h', 'Shell'],
			':shell4:'       => ['shell4', 'Shell'],
			':hshell4:'      => ['shell4_h', 'Shell'],

			':flag:'         => ['flag', 'Flag', ':flags:'],
			':hflag:'        => ['flag_h', 'Flag', ':hflags:'],
			':flag2:'        => ['flag2', 'Flag', ':flags2:'],
			':hflag2:'       => ['flag2_h', 'Flag', ':hflags2:'],
			':flag3:'        => ['flag3', 'Flag', ':flags3:'],
			':hflag3:'       => ['flag3_h', 'Flag', ':hflags3:'],
			':flag4:'        => ['flag4', 'Flag', ':flags4:'],
			':hflag4:'       => ['flag4_h', 'Flag', ':flags4:'],
			':flag5:'        => ['flag5', 'Flag', ':flags5:'],
			':hflag5:'       => ['flag5_h', 'Flag', ':flags5:'],
			':flag6:'        => ['flag6', 'Flag', ':flags6:'],
			':hflag6:'       => ['flag6_h', 'Flag', ':flags6:'],

			':star:'         => ['star', 'Star'],
			':hstar:'        => ['star_h', 'Star'],
			':star2:'        => ['star2', 'Star'],
			':hstar2:'       => ['star2_h', 'Star'],
			':star3:'        => ['star3', 'Star'],
			':hstar3:'       => ['star3_h', 'Star'],
			':star4:'        => ['star4', 'Star'],
			':hstar4:'       => ['star4_h', 'Star'],
			':star5:'        => ['star5', 'Star'],
			':hstar5:'       => ['star5_h', 'Star'],

			':related:'      => ['related', 'Related'],
			':hrelated:'     => ['related_h', 'Related'],
			':related2:'     => ['related2', 'Related'],
			':hrelated2:'    => ['related2_h', 'Related'],

			':install:'      => ['install', 'Install'],
			':hinstall:'     => ['install_h', 'Install'],
			':install2:'     => ['install2', 'Install'],
			':hinstall2:'    => ['install2_h', 'Install'],
			':install3:'     => ['install3', 'Install'],
			':hinstall3:'    => ['install3_h', 'Install'],
			':install4:'     => ['install4', 'Install'],
			':hinstall4:'    => ['install4_h', 'Install'],
			':install5:'     => ['install5', 'Install'],
			':hinstall5:'    => ['install5_h', 'Install'],

			':network:'      => ['network', 'Network'],
			':hnetwork:'     => ['network_h', 'Network'],
			':network2:'     => ['network2', 'Network'],
			':hnetwork2:'    => ['network2_h', 'Network'],
			':network3:'     => ['network3', 'Network'],
			':hnetwork3:'    => ['network3_h', 'Network'],
			':network4:'     => ['network4', 'Network'],
			':hnetwork4:'    => ['network4_h', 'Network'],
			':network5:'     => ['network5', 'Network'],
			':hnetwork5:'    => ['network5_h', 'Network'],

			':code:'         => ['code', 'Code'],
			':hcode:'        => ['code_h', 'Code'],
			':code2:'        => ['code2', 'Code'],
			':hcode2:'       => ['code2_h', 'Code'],
			':code3:'        => ['code3', 'Code'],
			':hcode3:'       => ['code3_h', 'Code'],
			':code4:'        => ['code4', 'Code'],
			':hcode4:'       => ['code4_h', 'Code'],
			':code5:'        => ['code5', 'Code'],
			':hcode5:'       => ['code5_h', 'Code'],
			':code6:'        => ['code6', 'Code'],
			':hcode6:'       => ['code6_h', 'Code'],


			':hd:'           => ['hd', 'Hard Drive', ':harddrive:', ':hds:', ':harddrives:'],
			':hhd:'          => ['hd_h', 'Hard Drive', ':hharddrive:', ':hhds:', ':hharddrives:'],
			':hd2:'          => ['hd2', 'Hard Drive', ':harddrive2:', ':hds2:', ':harddrives2:'],
			':hhd2:'         => ['hd2_h', 'Hard Drive', ':hharddrive2:', ':hhds2:', ':hharddrives2:'],
			':hd3:'          => ['hd3', 'Hard Drive', ':harddrive3:', ':hds3:', ':harddrives3:'],
			':hhd3:'         => ['hd3_h', 'Hard Drive', ':hharddrive3:', ':hhds3:', ':hharddrives3:'],

			':computer:'     => ['computer', 'Computer'],
			':hcomputer:'    => ['computer_h', 'Computer'],
			':computer2:'    => ['computer2', 'Computer'],
			':hcomputer2:'   => ['computer2_h', 'Computer'],
			':computer3:'    => ['computer3', 'Computer'],
			':hcomputer3:'   => ['computer3_h', 'Computer'],
			':computer4:'    => ['computer4', 'Computer'],
			':hcomputer4:'   => ['computer4_h', 'Computer'],
			':computer5:'    => ['computer5', 'Computer'],
			':hcomputer5:'   => ['computer5_h', 'Computer'],

			':server:'       => ['server', 'Server'],
			':hserver:'      => ['server_h', 'Server'],
			':server2:'      => ['server2', 'Server'],
			':hserver2:'     => ['server2_h', 'Server'],
			':server3:'      => ['server3', 'Server'],
			':hserver3:'     => ['server3_h', 'Server'],

			':device:'       => ['device', 'Device'],
			':hdevice:'      => ['device_h', 'Device'],
			':device2:'      => ['device2', 'Device'],
			':hdevice2:'     => ['device2_h', 'Device'],
			':device3:'      => ['device3', 'Device'],
			':hdevice3:'     => ['device3_h', 'Device'],

			':os:'           => ['os', 'OS'],
			':hos:'          => ['os_h', 'OS'],
			':os2:'          => ['os2', 'OS'],
			':hos2:'         => ['os2_h', 'OS'],
			':os3:'          => ['os3', 'OS'],
			':hos3:'         => ['os3_h', 'OS'],
			':os4:'          => ['os4', 'OS'],
			':hos4:'         => ['os4_h', 'OS'],

			':ram:'          => ['ram', 'RAM'],
			':hram:'         => ['ram_h', 'RAM'],
			':ram2:'         => ['ram2', 'RAM'],
			':hram2:'        => ['ram2_h', 'RAM'],
			':ram3:'         => ['ram3', 'RAM'],
			':hram3:'        => ['ram3_h', 'RAM'],

			':task:'         => ['task', 'Task'],
			':htask:'        => ['task_h', 'Task'],
			':task2:'        => ['task2', 'Task'],
			':htask2:'       => ['task2_h', 'Task'],
			':task3:'        => ['task3', 'Task'],
			':htask3:'       => ['task3_h', 'Task'],
			':task4:'        => ['task4', 'Task'],
			':htask4:'       => ['task4_h', 'Task'],

			':calendar:'     => ['calendar', 'Calendar'],
			':hcalendar:'    => ['calendar_h', 'Calendar'],
			':calendar2:'    => ['calendar2', 'Calendar'],
			':hcalendar2:'   => ['calendar2_h', 'Calendar'],
			':calendar3:'    => ['calendar3', 'Calendar'],
			':hcalendar3:'   => ['calendar3_h', 'Calendar'],
			':calendar4:'    => ['calendar4', 'Calendar'],
			':hcalendar4:'   => ['calendar4_h', 'Calendar'],

			':user:'         => ['user', 'User'],
			':huser:'        => ['user_h', 'User'],
			':user2:'        => ['user2', 'User'],
			':huser2:'       => ['user2_h', 'User'],
			':user3:'        => ['user3', 'User'],
			':huser3:'       => ['user3_h', 'User'],
			':user4:'        => ['user4', 'User'],
			':huser4:'       => ['user4_h', 'User'],
			':user5:'        => ['user5', 'User'],
			':huser5:'       => ['user5_h', 'User'],

			':archive:'      => ['archive', 'Archive'],
			':harchive:'     => ['archive_h', 'Archive'],
			':archive2:'     => ['archive2', 'Archive'],
			':harchive2:'    => ['archive2_h', 'Archive'],
			':archive3:'     => ['archive3', 'Archive'],
			':harchive3:'    => ['archive3_h', 'Archive'],

			':lock:'         => ['lock', 'Lock'],
			':hlock:'        => ['lock_h', 'Lock'],
			':lock2:'        => ['lock2', 'Lock'],
			':hlock2:'       => ['lock2_h', 'Lock'],
			':lock3:'        => ['lock3', 'Lock'],
			':hlock3:'       => ['lock3_h', 'Lock'],
			':lock4:'        => ['lock4', 'Lock'],
			':hlock4:'       => ['lock4_h', 'Lock'],
			':lock5:'        => ['lock5', 'Lock'],
			':hlock5:'       => ['lock5_h', 'Lock'],
			':lock6:'        => ['lock6', 'Lock'],
			':hlock6:'       => ['lock6_h', 'Lock'],
			':lock7:'        => ['lock7', 'Lock'],
			':hlock7:'       => ['lock7_h', 'Lock'],
		],
		'auto_nose' => true
	];

	var $_smileys = array();

	function __construct(&$obj)
	{
		$default = $this->conf;
		parent::Text_Wiki_Parse($obj);

		// read the list of smileys to sort out variantes and :xxx: while building the regexp
		$this->_smileys = $this->getConf('smileys', $default['smileys']);
		$autoNose = $this->getConf('auto_nose', $default['auto_nose']);

		// If you define $global_smileys in your global mrcore topic using <phpw> then I will merge those here
		// mReschke 2014-04-30
		global $global_smileys;
		if (isset($global_smileys)) {
			$this->_smileys = array_merge($this->_smileys, $global_smileys);
		}

		$reg1 = $reg2 = '';
		$sep1 = ':(?:';
		$sep2 = '';
		foreach ($this->_smileys as $smiley => $def) {
			for ($i = 1; $i < count($def); $i++) {
				if ($i > 1) {
					$cur = $def[$i];
					$this->_smileys[$cur] = &$this->_smileys[$smiley];
				} else {
					$cur = $smiley;
				}
				$len = strlen($cur);
				if (($cur{0} == ':') && ($len > 2) && ($cur{$len - 1} == ':')) {
					$reg1 .= $sep1 . preg_quote(substr($cur, 1, -1), '#');
					$sep1 = '|';
					continue;
				}
				if ($autoNose && ($len === 2)) {
					$variante = $cur{0} . '-' . $cur{1};
					$this->_smileys[$variante] = &$this->_smileys[$smiley];
					$cur = preg_quote($cur{0}, '#') . '-?' . preg_quote($cur{1}, '#');
				} else {
					$cur = preg_quote($cur, '#');
				}
				$reg2 .= $sep2 . $cur;
				$sep2 = '|';
			}
		}
		#$delim = '[\n\r\s' . $this->wiki->delim . '$^]';
		$delim = '[\n\r\s\>' . $this->wiki->delim . '$^]'; //mreschke added the \> so I can use <box :someicon:>...</box> and it still work with > after last :
		$this->regex = '#(?<=' . $delim .
			 ')(' . ($reg1 ? $reg1 . '):' . ($reg2 ? '|' : '') : '') . $reg2 .
			 ')(?=' . $delim . ')#i';
	}

	function process(&$matches)
	{
		// tokenize
		return $this->wiki->addToken($this->rule,
			array(
				'symbol' => $matches[1],
				'name'   => $this->_smileys[$matches[1]][0],
				'desc'   => $this->_smileys[$matches[1]][1]
			));
	}
}
