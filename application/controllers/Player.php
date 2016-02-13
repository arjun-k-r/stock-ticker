<?php
/**
 * Created by PhpStorm.
 * User: jojoman46
 * Date: 2016-02-12
 * Time: 5:54 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends Application {

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
     * map to /players.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        /* Grab data from database for Transactions and Players */
        $this->data['transactions'] = $this->transactions->getAllTransactions();

        $this->data['players'] = $this->players->getAllPlayers();
        $this->load->helper('form');

        /* Set up data to render page */
        $this->data['title'] = "Stock Ticker";
        $this->data['left-panel-content'] = 'player/players';
        $this->data['right-panel-content'] = 'player/transactions';


        if(empty($this->session->userdata('username'))) {
            $latestPlayer = $this->transactions->latestTransaction();
        } else
            $latestPlayer = $this->session->userdata('username');
        $this->data['Player'] = $latestPlayer;


        $form       = form_open('player/display');
        $player_cash  = array();
        $player_names  = array();
        $players     = $this->players->getAllPlayers();


        foreach( $players as $item )
        {
            array_push($player_names, $item->Player);
            array_push($player_cash, $item->Cash);

        }
        $players = array_combine($player_names, $player_names);

        $select                 = form_dropdown('player',
            $players, $latestPlayer,
            "class = 'form-control'" .
            "onchange = 'this.form.submit()'");

        $this->data['form']     = $form;
        $this->data['select']   = $select;
        $this->data['ptrans'] = $this->transactions->getPlayerTransactions($this->session->userdata('username'));
        $holdingsArray = $this->transactions->getCurrentHoldings($this->session->userdata('username'));
        $this->data['BOND'] =  $holdingsArray["BOND"];
        $this->data['GOLD'] =  $holdingsArray["GOLD"];
        $this->data['GRAN'] =  $holdingsArray["GRAN"];
        $this->data['IND'] =  $holdingsArray["IND"];
        $this->data['OIL'] =  $holdingsArray["OIL"];
        $this->data['TECH'] =  $holdingsArray["TECH"];



        $this->render();
    }

    public function display(){
        $this->data['transactions'] = $this->transactions->getAllTransactions();
        $this->data['players'] = $this->players->getAllPlayers();
        $this->load->helper('form');
        $code = $this->input->post('player');
        $this->data['Playername'] = $code;

        $this->data['title'] = "Stock Ticker";
        $this->data['left-panel-content'] = 'player/players';
        $this->data['right-panel-content'] = 'player/transactions';
        $this->data['player_code'] = $code;

        $form       = form_open('player/display');
        $player_cash  = array();
        $player_names  = array();
        $players     = $this->players->getAllPlayers();

        foreach( $players as $item )
        {
            array_push($player_names, $item->Player);
            array_push($player_cash, $item->Cash);

        }
        $players = array_combine($player_names, $player_names);

        $select                 = form_dropdown('player',
            $players,
            $code,
            "class = 'form-control'" .
            "onchange = 'this.form.submit()'");

        $this->data['form']     = $form;
        $this->data['select']   = $select;
        $this->data['ptrans'] = $this->transactions->getPlayerTransactions($code);
        $this->data['holdings'] = $this->transactions->getCurrentHoldings($code);

        $this->render();
    }
}