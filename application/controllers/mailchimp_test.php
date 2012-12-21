<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mailchimp_test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->library('mcapi');
		
		/*$name = 'Furby';
		$last_name = 'Alcantar';
		$mail = 'oscar.alcantars@gmail.com';

		if( $this->mcapi->listSubscribe($this->config->item('mcapi_list_id'), $mail, array('FNAME' => $name, 'LNAME' => $last_name)) === true){
			echo "Se a subscrito correctamente";
		}else{
			echo 'Ocurrio un errro al subcribirse';
		}*/

		$camps = $this->mcapi->campaigns(array('list_id' => $this->config->item('mcapi_list_id')) );
		var_dump($camps);
		foreach ($camps['data'] as $key => $value) {
			if (strpos($value['title'], 'Campañita') !== false) {
				echo "ddd";
				// var_dump($this->mcapi->campaignSchedule($value['id'], '2012-11-29 17:01:00'));
				// var_dump($this->mcapi->campaignReplicate($value['id']));
				// var_dump($this->mcapi->campaignSendNow($value['id']));
				// var_dump($this->mcapi->campaignSendTest($value['id'], array('gamameso@gmail.com')));
				var_dump($this->mcapi->campaignDelete($value['id']));
			}
		}


	}

	public function send_email(){
		$apikey = '7a2f3fa0034c583fdbad37fa9bc24457-us6';
 
		$to_emails = array('gamameso@gmail.com');
		$to_names = array('gamameso');
		

		$html = $this->load->view('html_campaign_mc', '', true);
		$message = array(
		    'html'=> 'Yo, this is the <b>html</b> portion',
		    'text'=>'Yo, this is the *text* portion',
		    'subject'=>'This is the subject',
		    'from_name'=>'Me!',
		    'from_email'=>'verifed@example.com',
		    'to_email'=>$to_emails,
		    'to_name'=>$to_names
		);
		 
		$tags = array('WelcomeEmail');
		 
		$params = array(
		    'apikey'=>$apikey,
		    'message'=> $message,
		    'track_opens'=>false,
		    'track_clicks'=>false,
		    'tags'=>$tags
		);
		var_dump(http_build_query($params, '&'));
		exit;
		 
		$url = "http://us6.sts.mailchimp.com/1.0/SendEmail";
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params, '&'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$result = curl_exec($ch);
		var_dump($result);
		curl_close ($ch);
		 
		$data = json_decode($result);
		echo "Status = ".$data->status."\n";
	}

	public function test_create_campaign(){
		echo 'pruebita para crear una campaña desde codigo :D<br>';

		$this->load->library('mcapi');
		
		$type = 'regular';
		$options = array(
								'list_id'=> $this->config->item('mcapi_list_id'),
								'title' => 'Campañita',
								'subject'=> 'Creando campaña desde codigo',
								'from_email'=> 'gamameso@gmail.com',
								'from_name'=> 'Gamaliel',
								'to_name'=> 'Yuppics'
						);

		$html = $this->load->view('html_campaign_mc', '', true);
		$text_plain = "Use this area to offer a short teaser of your email's content. Text here will show in the preview area of some email clients.
									------------------------------------------------------------
									*|ARCHIVE|*
									yuppics (http://yuppics.com)


									** Test mail
									------------------------------------------------------------


									** Heading 1
									------------------------------------------------------------


									** Heading 2
									------------------------------------------------------------


									** Heading 3
									------------------------------------------------------------


									** Heading 4
									------------------------------------------------------------
									Getting started: Customize your template by clicking on the style editor tabs up above. Set your fonts, colors, and styles. After setting your styling is all done you can click here in this area, delete the text, and start adding your own awesome content!

									After you enter your content, highlight the text you want to style and select the options you set in the style editor in the \"styles\" drop down box. Want to get rid of styling on a bit of text (http://www.mailchimp.com/kb/article/im-using-the-style-designer-and-i-cant-get-my-formatting-to-change) , but having trouble doing it? Just use the \"clear styles\" button to strip the text of any formatting and reset your style.

									Repeatable content blocks: Repeatable sections are noted with plus and minus signs so that you can add and subtract content blocks. You can also get a little fancy (http://www.mailchimp.com/kb/article/how-do-i-work-with-repeatable-content-blocks) : repeat blocks and remove all text to make image \"gallery\" sections, or do the opposite and remove images for text-only blocks!

									============================================================
									Copyright © *|CURRENT_YEAR|* *|LIST:COMPANY|*, All rights reserved.
									*|IFNOT:ARCHIVE_PAGE|* *|LIST:DESCRIPTION|*
									Our mailing address is:
									*|LIST_ADDRESS|**|END:IF|*
									*|IF:REWARDS|* *|REWARDS_TEXT|* *|END:IF|*

									** follow on Twitter (*|TWITTER:PROFILEURL [format=text]|*)
									** friend on Facebook (*|FACEBOOK:PROFILEURL [format=text]|*)
									** forward to a friend (*|FORWARD|*)
									** unsubscribe from this list (*|UNSUB|*)
									** update subscription preferences (*|UPDATE_PROFILE|*)";

		$content = array(
								'html' => $html,
								'text' => $text_plain);

		var_dump($this->mcapi->campaignCreate($type, $options, $content));
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */