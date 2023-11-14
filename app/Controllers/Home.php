<?php

namespace App\Controllers;
use CodeIgniter\Controllers;
use App\Models\M_model;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    public function index()
    {
        if(session()->get('level')!= null) {
        $previousURL = previous_url(); 
        
        if ($previousURL) {
            return redirect()->to($previousURL); 
        }

        }else{

            $model=new M_model();
            $where=array('dipakai'=>'Y');
            
            $cekSekolah=$model->getRow('settings_website',$where);
            session()->set('foto_sekolah',$cekSekolah->icon);
            session()->set('logo_sekolah',$cekSekolah->logo);
            session()->set('text_sekolah',$cekSekolah->text);
            session()->set('login_sekolah',$cekSekolah->login);
            session()->set('nama_website',$cekSekolah->nama_website);

            echo view('login');
        }
    }

    public function aksi_login()
    {
        $n=$this->request->getPost('username'); 
        $p=$this->request->getPost('password');

        $captchaResponse = $this->request->getPost('g-recaptcha-response');
        $captchaSecretKey = '6Le4D6snAAAAAHD3_8OPnw4teaKXWZdefSyXn4H3';

        $verifyCaptchaResponse = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$captchaSecretKey}&response={$captchaResponse}"
        );

        $captchaData = json_decode($verifyCaptchaResponse);

        if (!$captchaData->success) {

            session()->setFlashdata('error', 'CAPTCHA verification failed. Please try again.');
            return redirect()->to('/Home');
        }

        $model= new M_model();
        $data=array(
            'username'=>$n, 
            'password'=>md5($p)
        );
        $cek=$model->getarray('user', $data);
        if ($cek>0) {
            $where=array('id_pegawai_user'=>$cek['id_user']);
            $pegawai=$model->getarray('pegawai', $where);

                if ($pegawai) { 
                session()->set('id', $cek['id_user']);
                session()->set('username', $cek['username']);
                session()->set('nama_pegawai', $pegawai['nama_pegawai']);
                session()->set('level', $cek['level']);

                $id = session()->get('id');
                $kui=array(
                    'id_user_log'=>session()->get('id'),
                    'activity'=>"Login pada aplikasi mini bank dengan ID ". $id." ",
                    'tanggal_activity'=>date('Y-m-d H:i:s')
                );
                $model->simpan('log_activity',$kui);

                return redirect()->to('/home/dashboard');
            } else {
                $where = array('id_nasabah_user' => $cek['id_user']);
                $nasabah = $model->getarray('nasabah', $where);

                if ($nasabah) { 
                    session()->set('id', $cek['id_user']);
                    session()->set('username', $cek['username']);
                    session()->set('nama_nasabah', $nasabah['nama_nasabah']);
                    session()->set('id_nasabah', $nasabah['id_nasabah']);
                    session()->set('level', $cek['level']);

                    $kui=array(
                        'id_user_log'=>session()->get('id'),
                        'activity'=>"Login pada aplikasi mini bank dengan ID ". $id." ",
                        'tanggal_activity'=>date('Y-m-d H:i:s')
                    );
                    $model->simpan('log_activity',$kui);

                    return redirect()->to('/home/dashboard');
                }
            }
        }
        return redirect()->to('/');
    }

    public function log_out()
    {
        $model = new M_model(); 
        session()->destroy();

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Log Out pada aplikasi mini bank dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/');
    }

    public function settings_control()
    {
        if(session()->get('level')== 1) {

            $id=session()->get('id');
            $where=array('id_settings'=> 1);
            $model=new M_model();
            $pakif['use']=$model->getRow('settings_website',$where);

            $id=session()->get('id');
            $where=array('id_user'=>$id);

            $where=array('id_user' => session()->get('id'));
            $kui['foto']=$model->getRow('user',$where);

            echo view('header', $kui);
            echo view('menu');
            echo view('settings', $pakif);
            echo view('footer');
        }else {
            return redirect()->to('/');
        }
    }

    public function aksi_ganti_website()
    {
        $model = new M_model();
        $id = $this->request->getPost('id');
        $where = array('id_settings' => $id);
        
        $logo = array();

        $logo_web = $this->request->getFile('logo');
        $photo = $this->request->getFile('icon');
        $text = $this->request->getFile('text'); 
        $login = $this->request->getFile('login'); 

        if ($logo_web && $logo_web->isValid()) {
            $textlogo = $logo_web->getRandomName();
            $logo_web->move(PUBLIC_PATH . '/assets/images/settings_web/', $textlogo);
            $logo['logo'] = $textlogo;
        }

        if ($photo && $photo->isValid()) {
            $img = $photo->getRandomName();
            $photo->move(PUBLIC_PATH . '/assets/images/settings_web/', $img);
            $logo['icon'] = $img;
        }

        if ($text && $text->isValid()) {
            $textFileName = $text->getRandomName();
            $text->move(PUBLIC_PATH . '/assets/images/settings_web/', $textFileName);
            $logo['text'] = $textFileName;
        }

        if ($login && $login->isValid()) {
            $loginFileName = $login->getRandomName();
            $login->move(PUBLIC_PATH . '/assets/images/settings_web/', $loginFileName);
            $logo['login'] = $loginFileName;
        }

        $nama_website = $this->request->getPost('nama_website');
        if (!empty($nama_website)) {
            $logo['nama_website'] = $nama_website;
        }

        if (!empty($logo)) {
            $model->edit('settings_website', $logo, $where);
        }

        return redirect()->to('/home/log_out');
    }

    public function dashboard()
    {
        if (!session()->get('id') > 0) {
            return redirect()->to('/home/dashboard');
        }

        $model = new M_model();

        $penyetoran = ['id_nasabah_penyetoran' => session()->get('id_nasabah')];
        $penarikan = ['id_nasabah_penarikan' => session()->get('id_nasabah')];
        $where = ['id_user' => session()->get('id')];
        $kui['foto'] = $model->getRow('user', $where);

        if (session()->get('level') >= 1 && session()->get('level') <= 3) {
            $kui['penyetoran'] = $model->sumData('transaksi_penyetoran', 'jumlah_penyetoran');
            $kui['penarikan'] = $model->sumDataStatus('transaksi_penarikan', 'jumlah_penarikan');
        } elseif (session()->get('level') == 4) {
            $kui['penyetoran'] = $model->sumDataWithWhere('transaksi_penyetoran', 'jumlah_penyetoran', $penyetoran);
            $kui['penarikan'] = $model->sumDataStatusWhere('transaksi_penarikan', 'jumlah_penarikan', $penarikan);
        }

        echo view('header', $kui);
        echo view('menu');
        echo view('dashboard');
        echo view('footer');
    }


    public function pegawai()
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $on='pegawai.maker_pegawai=user.id_user';
        $kui['duar']=$model->fusionOderBy('pegawai', 'user', $on,  'tanggal_pegawai');

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/pegawai/pegawai');
        echo view('footer'); 

        }else{
            return redirect()->to('/');
        }
    }

    public function detail_pegawai($id)
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $where2=array('id_pegawai_user'=>$id); 
        $on='pegawai.id_pegawai_user=user.id_user';
        $kui['gas']=$model->detail('pegawai', 'user',$on, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/pegawai/detail_pegawai');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function pegawai_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1) {

            $model=new M_model();
            $on='pegawai.maker_pegawai=user.id_user';
            $where=$this->request->getPost('search_pegawai');
            $kui['duar']=$model->superLike2('pegawai', 'user', $on, 'pegawai.nip','pegawai.nama_pegawai', $where);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view ('header', $kui);
        echo view ('menu');
        echo view('user/pegawai/pegawai');
        echo view ('footer');
    }

    public function tambah_pegawai()
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $on='pegawai.maker_pegawai=user.id_user';
        $kui['duar']=$model->fusionOderBy('pegawai', 'user', $on,  'tanggal_pegawai');

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/pegawai/tambah_pegawai');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function aksi_tambah_pegawai()
    {
        $model=new M_model();

        $nip=$this->request->getPost('nip');
        $nama_pegawai=$this->request->getPost('nama_pegawai');
        $no_telp_pegawai=$this->request->getPost('no_telp_pegawai');
        $jk_pegawai=$this->request->getPost('jk_pegawai');
        $ttl_pegawai=$this->request->getPost('ttl_pegawai');
        $alamat_pegawai=$this->request->getPost('alamat_pegawai');
        $username=$this->request->getPost('username');
        $level=$this->request->getPost('level');
        $maker_pegawai=session()->get('id');

        $user=array(
            'username'=>$username,
            'password'=>md5('@dmin123'),
            'level'=>$level,
        );

        $model=new M_model();
        $model->simpan('user', $user);
        $where=array('username'=>$username);
        $id=$model->getarray('user', $where);
        $iduser = $id['id_user'];

        $pegawai = array(
            'nip' => $nip,
            'nama_pegawai' => $nama_pegawai,
            'no_telp_pegawai' => $no_telp_pegawai,
            'jk_pegawai' => $jk_pegawai,
            'ttl_pegawai' => $ttl_pegawai,
            'alamat_pegawai' => $alamat_pegawai,
            'id_pegawai_user' => $iduser,
            'maker_pegawai' => $maker_pegawai,
        );

        $model->simpan('pegawai', $pegawai);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menambah akun pegawai dengan username ". $username." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/pegawai');
    }

    public function reset_pw($id)
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $where=array('id_user'=>$id);
        $data=array(
            'password'=>md5('@dmin123')
        );
        $model->edit('user',$data,$where);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Mereset password akun pegawai dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/pegawai');

        }else{
            return redirect()->to('/');
        }
    }

    public function edit_pegawai($id)
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $where2=array('pegawai.id_pegawai_user'=>$id);

        $on='pegawai.id_pegawai_user=user.id_user';
        $kui['duar']=$model->edit_user('pegawai', 'user',$on, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/pegawai/edit_pegawai');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function aksi_edit_pegawai()
    {
        $id= $this->request->getPost('id');    
        $nip=$this->request->getPost('nip');
        $nama_pegawai=$this->request->getPost('nama_pegawai');
        $no_telp_pegawai=$this->request->getPost('no_telp_pegawai');
        $jk_pegawai=$this->request->getPost('jk_pegawai');
        $ttl_pegawai=$this->request->getPost('ttl_pegawai');
        $alamat_pegawai=$this->request->getPost('alamat_pegawai');
        $username=$this->request->getPost('username');
        $level=$this->request->getPost('level');
        $maker_pegawai=session()->get('id');

        $where=array('id_user'=>$id);    
        $where2=array('id_pegawai_user'=>$id);
        if ($password !='') {
            $user=array(
                'username'=>$username,
                'level'=>$level,
            );
        }else{
            $user=array(
                'username'=>$username,
                'level'=>$level,
            );
        }
        
        $model=new M_model();
        $model->edit('user', $user,$where);

        $pegawai=array(
            'nip' => $nip,
            'nama_pegawai' => $nama_pegawai,
            'no_telp_pegawai' => $no_telp_pegawai,
            'jk_pegawai' => $jk_pegawai,
            'ttl_pegawai' => $ttl_pegawai,
            'alamat_pegawai' => $alamat_pegawai,
            'maker_pegawai' => $maker_pegawai,
        );

        $model->edit('pegawai', $pegawai, $where2);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Mengedit akun pegawai dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/pegawai');
    }

    public function hapus_pegawai($id)
    {
        if(session()->get('level')== 1) {

        $model=new M_model();
        $where2=array('id_user'=>$id);
        $where=array('id_pegawai_user'=>$id);

        $model->hapus('pegawai',$where);
        $model->hapus('user',$where2);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menghapus akun pegawai dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/pegawai');

        }else{
            return redirect()->to('/');
        }
    }

    public function nasabah()
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $on='nasabah.maker_nasabah=user.id_user';
        $kui['duar']=$model->fusionOderBy('nasabah', 'user', $on,  'tanggal_nasabah');

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/nasabah/nasabah');
        echo view('footer'); 

        }else{
            return redirect()->to('/');
        }
    }

    public function detail_nasabah($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $where2=array('id_nasabah_user'=>$id); 
        $on='nasabah.id_nasabah_user=user.id_user';
        $kui['gas']=$model->detail('nasabah', 'user',$on, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/nasabah/detail_nasabah');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function nasabah_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1 || session()->get('level')== 3) {

            $model=new M_model();
            $on='nasabah.maker_nasabah=user.id_user';
            $where=$this->request->getPost('search_nasabah');
            $kui['duar']=$model->superLike2('nasabah', 'user', $on, 'nasabah.nik','nasabah.nama_nasabah', $where);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view ('header', $kui);
        echo view ('menu');
        echo view('user/nasabah/nasabah');
        echo view ('footer');
    }

    public function tambah_nasabah()
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $on='nasabah.maker_nasabah=user.id_user';
        $kui['duar']=$model->fusionOderBy('nasabah', 'user', $on,  'tanggal_nasabah');

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/nasabah/tambah_nasabah');
        echo view('footer');

         }else{
            return redirect()->to('/');
        }
    }

    public function aksi_tambah_nasabah()
    {
        $model=new M_model();

        $nik=$this->request->getPost('nik');
        $nama_nasabah=$this->request->getPost('nama_nasabah');
        $no_telp_nasabah=$this->request->getPost('no_telp_nasabah');
        $jk_nasabah=$this->request->getPost('jk_nasabah');
        $ttl_nasabah=$this->request->getPost('ttl_nasabah');
        $alamat_nasabah=$this->request->getPost('alamat_nasabah');
        $username=$this->request->getPost('username');
        $password=$this->request->getPost('password');
        $level=$this->request->getPost('level');
        $maker_nasabah=session()->get('id');

        $user=array(
            'username'=>$username,
            'password'=>md5($password),
            'level'=>'4',
        );

        $model=new M_model();
        $model->simpan('user', $user);
        $where=array('username'=>$username);
        $id=$model->getarray('user', $where);
        $iduser = $id['id_user'];

        $nasabah = array(
            'nik' => $nik,
            'nama_nasabah' => $nama_nasabah,
            'no_telp_nasabah' => $no_telp_nasabah,
            'jk_nasabah' => $jk_nasabah,
            'ttl_nasabah' => $ttl_nasabah,
            'alamat_nasabah' => $alamat_nasabah,
            'id_nasabah_user' => $iduser,
            'maker_nasabah' => $maker_nasabah,
        );

        $model->simpan('nasabah', $nasabah);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menambah akun nasabah dengan username ". $username." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/nasabah');
    }

    public function reset_pw_nasabah($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $where=array('id_user'=>$id);
        $data=array(
            'password'=>md5('@dmin123')
        );
        $model->edit('user',$data,$where);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Mereset password akun nasabah dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/nasabah');

         }else{
            return redirect()->to('/');
        }
    }

    public function edit_nasabah($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $where2=array('nasabah.id_nasabah_user'=>$id);

        $on='nasabah.id_nasabah_user=user.id_user';
        $kui['duar']=$model->edit_user('nasabah', 'user',$on, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('user/nasabah/edit_nasabah');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function aksi_edit_nasabah()
    {
        $id= $this->request->getPost('id');    
        $nik=$this->request->getPost('nik');
        $nama_nasabah=$this->request->getPost('nama_nasabah');
        $no_telp_nasabah=$this->request->getPost('no_telp_nasabah');
        $jk_nasabah=$this->request->getPost('jk_nasabah');
        $ttl_nasabah=$this->request->getPost('ttl_nasabah');
        $alamat_nasabah=$this->request->getPost('alamat_nasabah');
        $username=$this->request->getPost('username');
        $password=$this->request->getPost('password');
        $maker_nasabah=session()->get('id');

        $where=array('id_user'=>$id);    
        $where2=array('id_nasabah_user'=>$id);
        if ($password !='') {
            $user=array(
                'username'=>$username,
                'password'=>md5($password),
            );
        }else{
            $user=array(
                'username'=>$username,
                'password'=>md5($password),
            );
        }
        
        $model=new M_model();
        $model->edit('user', $user,$where);

        $nasabah=array(
            'nik' => $nik,
            'nama_nasabah' => $nama_nasabah,
            'no_telp_nasabah' => $no_telp_nasabah,
            'jk_nasabah' => $jk_nasabah,
            'ttl_nasabah' => $ttl_nasabah,
            'alamat_nasabah' => $alamat_nasabah,
            'maker_nasabah' => $maker_nasabah,
        );

        $model->edit('nasabah', $nasabah, $where2);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Mengedit akun nasabah dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/nasabah');
    }

    public function hapus_nasabah($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 3) {

        $model=new M_model();
        $where2=array('id_user'=>$id);
        $where=array('id_nasabah_user'=>$id);

        $model->hapus('nasabah',$where);
        $model->hapus('user',$where2);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menghapus akun nasabah dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/nasabah');

        }else{
            return redirect()->to('/');
        }
    }

    public function penyetoran()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
        }

        if(session()->get('level')== 1 || session()->get('level')== 2) {

            $model=new M_model();
            $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
            $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
            $kui['duar']=$model->superOderBy('transaksi_penyetoran', 'nasabah', 'user', $on, $on2, 'tanggal_penyetoran_urut');
        }

        if(session()->get('level')== 4) {

            $model=new M_model();
            $where=array('nama_nasabah'=>session()->get('nama_nasabah'));
            $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
            $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
            $kui['duar']=$model->penyetoran_mini_bank('transaksi_penyetoran', 'nasabah', 'user', $on, $on2,  'tanggal_penyetoran_urut', $where);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penyetoran/penyetoran');
        echo view('footer'); 
    }

    public function detail_penyetoran($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 2) {

        $model=new M_model();
        $where2=array('id_penyetoran'=>$id); 
        $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
        $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
        $kui['gas']=$model->detail_penyetoran('transaksi_penyetoran', 'nasabah', 'user',$on, $on2, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penyetoran/detail_penyetoran');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function penyetoran_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1 || session()->get('level')== 2) {

            $model=new M_model();
            $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
            $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
            $where=$this->request->getPost('search_penyetoran');
            $kui['duar']=$model->search_setor('transaksi_penyetoran', 'nasabah', 'user', $on, $on2, 'nasabah.nik','nasabah.nama_nasabah', $where);
        }

        if(session()->get('level')== 4) {

            $model=new M_model();
            $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
            $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
            $where2=array('nama_nasabah'=>session()->get('nama_nasabah'));
            $where=$this->request->getPost('search_penyetoran');
            $kui['duar']=$model->search_setor_4('transaksi_penyetoran', 'nasabah', 'user', $on, $on2, 'nasabah.nik','nasabah.nama_nasabah', $where, $where2);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penyetoran/penyetoran');
        echo view('footer');
    }

    public function tambah_penyetoran()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2) {

        $model = new M_model();
        $on='transaksi_penyetoran.id_nasabah_penyetoran=nasabah.id_nasabah';
        $on2='transaksi_penyetoran.maker_penyetoran=user.id_user';
        $kui['duar']=$model->superOderBy('transaksi_penyetoran', 'nasabah', 'user', $on, $on2, 'tanggal_penyetoran_urut');

        $id = session()->get('id');
        $where = array('id_user' => $id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto'] = $model->getRow('user', $where);

        $kui['n'] = $model->tampil('nasabah');

        echo view('header',$kui);
        echo view('menu');
        echo view('penyetoran/tambah_penyetoran');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function aksi_tambah_penyetoran()
    {
        $model=new M_model();
        $nasabah=$this->request->getPost('id_nasabah');
        $jumlah_penyetoran=$this->request->getPost('jumlah_penyetoran');
        $maker_penyetoran=session()->get('id');
        $data=array(

            'id_nasabah_penyetoran'=>$nasabah,
            'jumlah_penyetoran'=>$jumlah_penyetoran,
            'jenis_penyetoran'=>'Cash',
            'maker_penyetoran'=>$maker_penyetoran
        );

        $model->simpan('transaksi_penyetoran',$data);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menambah data transaksi penyetoran pada akun nasabah dengan ID ". $nasabah." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/penyetoran');
    }

    public function hapus_penyetoran($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 2) {

        $model=new M_model();
        $where=array('id_penyetoran'=>$id);
        $model->hapus('transaksi_penyetoran',$where);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menghapus data transaksi penyetoran dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('home/penyetoran');

        }else{
            return redirect()->to('/');
        }
    }

    public function penarikan()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
        }

        if(session()->get('level')== 1 || session()->get('level')== 2) {

            $model=new M_model();
            $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
            $on2='transaksi_penarikan.maker_penarikan=user.id_user';
            $kui['duar']=$model->superOderBy('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'tanggal_penarikan_urut');
        }

        if(session()->get('level')== 4) {

            $model=new M_model();
            $where=array('nama_nasabah'=>session()->get('nama_nasabah'));
            $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
            $on2='transaksi_penarikan.maker_penarikan=user.id_user';
            $kui['duar']=$model->penyetoran_mini_bank('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'tanggal_penarikan_urut', $where);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penarikan/penarikan');
        echo view('footer'); 
    }

    public function detail_penarikan($id)
    {
        if(session()->get('level')== 1 || session()->get('level')== 2) {

        $model=new M_model();
        $where2=array('id_penarikan'=>$id); 
        $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
        $on2='transaksi_penarikan.maker_penarikan=user.id_user';
        $kui['gas']=$model->detail_penyetoran('transaksi_penarikan', 'nasabah', 'user',$on, $on2, $where2);

        $id=session()->get('id');
        $where=array('id_user'=>$id);

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penarikan/detail_penarikan');
        echo view('footer');

        }else{
            return redirect()->to('/');
        }
    }

    public function penarikan_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1 || session()->get('level')== 2) {

            $model=new M_model();
            $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
            $on2='transaksi_penarikan.maker_penarikan=user.id_user';
            $where=$this->request->getPost('search_penarikan');
            $kui['duar']=$model->search_setor('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'nasabah.nik','nasabah.nama_nasabah', $where);
        }

        if(session()->get('level')== 4) {

            $model=new M_model();
            $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
            $on2='transaksi_penarikan.maker_penarikan=user.id_user';
            $where2=array('nama_nasabah'=>session()->get('nama_nasabah'));
            $where=$this->request->getPost('search_penarikan');
            $kui['duar']=$model->search_setor_4('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'nasabah.nik','nasabah.nama_nasabah', $where, $where2);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('penarikan/penarikan');
        echo view('footer');
    }

    public function status()
    {
        if (session()->get('level') == 1 || session()->get('level') == 2) {
            $ids = $this->request->getPost('penarikan');

            if (is_array($ids)) {
                $model = new M_model();
                $data = array(
                    'status' => "Penarikan Berhasil"
                );

                foreach ($ids as $id) {
                    $where = array('id_penarikan ' => $id);
                    $model->edit('transaksi_penarikan', $data, $where);

                    $kui=array(
                        'id_user_log'=>session()->get('id'),
                        'activity'=>"Status penarikan berhasil dengan ID ". $id." ",
                        'tanggal_activity'=>date('Y-m-d H:i:s')
                    );
                    $model->simpan('log_activity',$kui);

                }

                return redirect()->to('home/penarikan');
            } else {
                return redirect()->to('home/penarikan')->with('error', 'Invalid input data');
            }
        } else {
            return redirect()->to('/home/dashboard');
        }
    }

    // public function tambah_penarikan()
    // {
    //     if (session()->get('level') == 4) {

    //     $model = new M_model();
    //     $on='transaksi_penarikan.id_nasabah_penarikan=nasabah.id_nasabah';
    //     $on2='transaksi_penarikan.maker_penarikan=user.id_user';
    //     $kui['duar']=$model->superOderBy('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'tanggal_penarikan_urut');

    //     $id = session()->get('id');
    //     $where = array('id_user' => $id);

    //     $where=array('id_user' => session()->get('id'));
    //     $kui['foto'] = $model->getRow('user', $where);

    //     $kui['n'] = $model->tampil('nasabah');

    //     echo view('header',$kui);
    //     echo view('menu');
    //     echo view('penarikan/tambah_penarikan');
    //     echo view('footer');

    //     } else {
    //         return redirect()->to('/home/dashboard');
    //     }
    // }

    public function tambah_penarikan()
    {
        if (session()->get('level') == 4) {
            $model = new M_model();
            $id_user = session()->get('id');

            $nasabah = $model->getRow('nasabah', ['id_nasabah_user' => $id_user]);

            if ($nasabah) {
                $on = 'transaksi_penarikan.id_nasabah_penarikan = nasabah.id_nasabah';
                $on2 = 'transaksi_penarikan.maker_penarikan = user.id_user';
                $kui['duar'] = $model->superOderBy('transaksi_penarikan', 'nasabah', 'user', $on, $on2, 'tanggal_penarikan_urut');

                $where = ['id_user' => $id_user];
                $kui['foto'] = $model->getRow('user', $where);

                $kui['n'] = [$nasabah]; 

                echo view('header', $kui);
                echo view('menu');
                echo view('penarikan/tambah_penarikan');
                echo view('footer');
            } else {
                return redirect()->to('/home/dashboard');
            }
        } else {
            return redirect()->to('/home/dashboard');
        }
    }

    public function aksi_tambah_penarikan()
    {
        $model = new M_model();
        $nasabah = $this->request->getPost('id_nasabah');
        $jumlah_penarikan = $this->request->getPost('jumlah_penarikan');
        $keterangan_penarikan = $this->request->getPost('keterangan_penarikan');
        $maker_penarikan = session()->get('id');

        if (empty($keterangan_penarikan)) {
            $keterangan_penarikan = "~";
        }

        $data = [
            'id_nasabah_penarikan' => $nasabah,
            'jumlah_penarikan' => $jumlah_penarikan,
            'keterangan_penarikan' => $keterangan_penarikan,
            'jenis_penarikan' => 'Cash',
            'status' => 'Proses',
            'maker_penarikan' => $maker_penarikan
        ];

        $model->simpan('transaksi_penarikan', $data);

         $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menambah data transaksi penarikan pada akun nasabah dengan ID ". $nasabah." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/penarikan');
    }

    public function hapus_penarikan($id)
    {
        if (session()->get('level') == 1 || session()->get('level') == 2) {

        $model=new M_model();
        $where=array('id_penarikan'=>$id);
        $model->hapus('transaksi_penarikan',$where);

         $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Menghapus data transaksi penarikan dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('home/penarikan');

        } else {
            return redirect()->to('/home/dashboard');
        }
    }

    public function log_activity()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 3 || session()->get('level')== 4) {

            $model=new M_model();
            $where=array('log_activity.id_user_log'=>session()->get('id'));
            $on='log_activity.id_user_log=user.id_user';
            $kui['duar'] = $model->log('log_activity', 'user', $on, $where, 'tanggal_activity');

            $id=session()->get('id');
            $where=array('id_user'=>$id);

            $where=array('id_user' => session()->get('id'));
            $kui['foto']=$model->getRow('user',$where);

            echo view ('header', $kui);
            echo view ('menu');
            echo view ('log_activity/log');
            echo view ('footer');

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function log_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 3 || session()->get('level')== 4) {

            $model=new M_model();
            $on='log_activity.id_user_log=user.id_user';
            $where2=array('username'=>session()->get('username'));
            $where=$this->request->getPost('search_log');
            $kui['duar']=$model->search_log('log_activity', 'user', $on, 'user.username','log_activity.activity','log_activity.tanggal_activity', $where, $where2);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('log_activity/log');
        echo view('footer');
    }

    public function log_activity_user()
    {
        $userLevel = session()->get('level');
        if ($userLevel == 1) {
            $model = new M_model();
            $where = [];

            if ($userLevel == 1) {
                $where['user.level <='] = 4;
                $where['user.level >='] = 1;
            }

            $on = 'log_activity.id_user_log=user.id_user';
            $kui['duar'] = $model->log('log_activity', 'user', $on, $where, 'tanggal_activity');

            $id = session()->get('id');
            $where = ['id_user' => $id];

            $where = ['id_user' => session()->get('id')];
            $kui['foto'] = $model->getRow('user', $where);

            echo view ('header', $kui);
            echo view ('menu');
            echo view ('log_activity/log_user');
            echo view ('footer');
        } else {
            return redirect()->to('/home/dashboard');
        }
    }

    public function log_user_search()
    {
        if(!session()->get('id') > 0){
            return redirect()->to('/home/dashboard');
    }

        if(session()->get('level')== 1) {

            $model=new M_model();
            $on='log_activity.id_user_log=user.id_user';
            $where=$this->request->getPost('search_log_user');
            $kui['duar']=$model->search_log_pegawai('log_activity', 'user', $on, 'user.username','log_activity.activity','log_activity.tanggal_activity', $where);
        }

        $id=session()->get('id');
        $where=array('id_user'=>$id);
        $kui['search']="on";

        $where=array('id_user' => session()->get('id'));
        $kui['foto']=$model->getRow('user',$where);

        echo view('header',$kui);
        echo view('menu');
        echo view('log_activity/log_user');
        echo view('footer');
    }

    public function profile_pegawai()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 3) {

            $id=session()->get('id');
            $where2=array('id_user'=>$id);
            $where=array('id_pegawai_user'=>$id);
            $model=new M_model();
            $pakif['users']=$model->edit_pp('pegawai',$where);
            $pakif['use']=$model->edit_pp('user',$where2);

            $kui['foto']=$model->getRow('user',$where2);

            $id=session()->get('id');


            echo view('header',$kui);
            echo view('menu');
            echo view('profile', $pakif);
            echo view('footer');
        }else {
            return redirect()->to('/');
        }
    }

    public function aksi_ganti_profile_pegawai()
    {
    $model= new M_model();
    $id=session()->get('id');
    $where=array('id_user'=>$id);
    $photo=$this->request->getFile('foto');
    $kui=$model->getRow('user',$where);
    if( $photo != '' ){}
        elseif($photo != '' && file_exists(PUBLIC_PATH."/assets/images/profile/".$kui->foto) ) 
        {
            unlink(PUBLIC_PATH."/assets/images/profile/".$kui->foto);
        }
        elseif($photo == '')
        {
            $username= $this->request->getPost('username');
            $nip= $this->request->getPost('nip');                    
            $nama_pegawai= $this->request->getPost('nama_pegawai');
            $no_telp_pegawai= $this->request->getPost('no_telp_pegawai');
            $jk_pegawai= $this->request->getPost('jk_pegawai');
            $ttl_pegawai= $this->request->getPost('ttl_pegawai');
            $alamat_pegawai= $this->request->getPost('alamat_pegawai');

            $user=array(
                'username'=>$username,
            );
            $model->edit('user', $user,$where);
            $where2=array('id_pegawai_user'=>$id);

            $pegawai=array(
                'nip'=>$nip,
                'nama_pegawai'=>$nama_pegawai,
                'no_telp_pegawai'=>$no_telp_pegawai,
                'jk_pegawai'=>$jk_pegawai,
                'ttl_pegawai'=>$ttl_pegawai,
                'alamat_pegawai'=>$alamat_pegawai,
            );
            $model->edit('pegawai', $pegawai, $where2);

            $data=array(
                'id_user_log'=>session()->get('id'),
                'activity'=>"Edit Profile Pegawai ". $nama_pegawai." ",
                'tanggal_activity'=>date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity',$data);

            return redirect()->to('/home/log_out');
        }

        $username= $this->request->getPost('username');
        $nip= $this->request->getPost('nip');                    
        $nama_pegawai= $this->request->getPost('nama_pegawai');
        $no_telp_pegawai= $this->request->getPost('no_telp_pegawai');
        $jk_pegawai= $this->request->getPost('jk_pegawai');
        $ttl_pegawai= $this->request->getPost('ttl_pegawai');
        $alamat_pegawai= $this->request->getPost('alamat_pegawai');

        $img = $photo->getRandomName();
        $photo->move(PUBLIC_PATH.'/assets/images/profile/',$img);
        $user=array(
            'username'=>$username,
            'foto'=>$img
        );
        $model=new M_model();
        $model->edit('user', $user,$where);

        $pegawai=array(
            'nip'=>$nip,
            'nama_pegawai'=>$nama_pegawai,
            'no_telp_pegawai'=>$no_telp_pegawai,
            'jk_pegawai'=>$jk_pegawai,
            'ttl_pegawai'=>$ttl_pegawai,
            'alamat_pegawai'=>$alamat_pegawai,
        );
        $where2=array('id_pegawai_user'=>$id);
        $model->edit('pegawai', $pegawai, $where2);

        $data=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Edit Profile Pegawai ". $nama_pegawai." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$data);

        return redirect()->to('/home/log_out');
    }

    public function profile_nasabah()
    {
        if(session()->get('level')== 4) {

            $id=session()->get('id');
            $where2=array('id_user'=>$id);
            $where=array('id_nasabah_user'=>$id);
            $model=new M_model();
            $pakif['users']=$model->edit_pp('nasabah',$where);
            $pakif['use']=$model->edit_pp('user',$where2);

            $kui['foto']=$model->getRow('user',$where2);

            $id=session()->get('id');


            echo view('header',$kui);
            echo view('menu');
            echo view('profile_nasabah', $pakif);
            echo view('footer');
        }else {
            return redirect()->to('/');
        }
    }

    public function aksi_ganti_profile_nasabah()
    {
    $model= new M_model();
    $id=session()->get('id');
    $where=array('id_user'=>$id);
    $photo=$this->request->getFile('foto');
    $kui=$model->getRow('user',$where);
    if( $photo != '' ){}
        elseif($photo != '' && file_exists(PUBLIC_PATH."/assets/images/profile/".$kui->foto) ) 
        {
            unlink(PUBLIC_PATH."/assets/images/profile/".$kui->foto);
        }
        elseif($photo == '')
        {
            $username= $this->request->getPost('username');
            $nik= $this->request->getPost('nik');                    
            $nama_nasabah= $this->request->getPost('nama_nasabah');
            $no_telp_nasabah= $this->request->getPost('no_telp_nasabah');
            $jk_nasabah= $this->request->getPost('jk_nasabah');
            $ttl_nasabah= $this->request->getPost('ttl_nasabah');
            $alamat_nasabah= $this->request->getPost('alamat_nasabah');

            $user=array(
                'username'=>$username,
            );
            $model->edit('user', $user,$where);
            $where2=array('id_nasabah_user'=>$id);

            $nasabah=array(
                'nik'=>$nik,
                'nama_nasabah'=>$nama_nasabah,
                'no_telp_nasabah'=>$no_telp_nasabah,
                'jk_nasabah'=>$jk_nasabah,
                'ttl_nasabah'=>$ttl_nasabah,
                'alamat_nasabah'=>$alamat_nasabah,
            );
            $model->edit('nasabah', $nasabah, $where2);

            $data=array(
                'id_user_log'=>session()->get('id'),
                'activity'=>"Edit Profile Nasabah ". $nama_nasabah." ",
                'tanggal_activity'=>date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity',$data);

            return redirect()->to('/home/log_out');
        }

        $username= $this->request->getPost('username');
        $nik= $this->request->getPost('nik');                    
        $nama_nasabah= $this->request->getPost('nama_nasabah');
        $no_telp_nasabah= $this->request->getPost('no_telp_nasabah');
        $jk_nasabah= $this->request->getPost('jk_nasabah');
        $ttl_nasabah= $this->request->getPost('ttl_nasabah');
        $alamat_nasabah= $this->request->getPost('alamat_nasabah');

        $img = $photo->getRandomName();
        $photo->move(PUBLIC_PATH.'/assets/images/profile/',$img);
        $user=array(
            'username'=>$username,
            'foto'=>$img
        );
        $model=new M_model();
        $model->edit('user', $user,$where);

        $nasabah=array(
            'nik'=>$nik,
            'nama_nasabah'=>$nama_nasabah,
            'no_telp_nasabah'=>$no_telp_nasabah,
            'jk_nasabah'=>$jk_nasabah,
            'ttl_nasabah'=>$ttl_nasabah,
            'alamat_nasabah'=>$alamat_nasabah,
        );
        $where2=array('id_nasabah_user'=>$id);
        $model->edit('nasabah', $nasabah, $where2);

        $data=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Edit Profile Nasabah ". $nama_nasabah." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$data);

        return redirect()->to('/home/log_out');
    }

    public function change_pw()  
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 3 || session()->get('level')== 4) {

            $id=session()->get('id');
            $where2=array('id_user'=>$id);
            $model=new M_model();
            $where=array('id_user' => session()->get('id'));
            $kui['foto']=$model->getRow('user',$where);
            $pakif['use']=$model->getRow('user',$where2);

            $id=session()->get('id');
            $where=array('id_user'=>$id);

            echo view('header',$kui);
            echo view('menu',$pakif);
            echo view('password',$pakif);
            echo view('footer');
        }else{
            return redirect()->to('/');
        }
    }

    public function aksi_change_pw()   
    {
        $pass=$this->request->getPost('pw');
        $id=session()->get('id');
        $model= new M_model();

        $data=array( 
            'password'=>md5($pass)
        );

        $where=array('id_user'=>$id);
        $model->edit('user', $data, $where);

        $kui=array(
            'id_user_log'=>session()->get('id'),
            'activity'=>"Mengganti password dengan ID ". $id." ",
            'tanggal_activity'=>date('Y-m-d H:i:s')
        );
        $model->simpan('log_activity',$kui);

        return redirect()->to('/home/log_out');
    }

    public function laporan_mini_bank()
    {
        if(session()->get('level')== 1 || session()->get('level')== 3 || session()->get('level')== 4) {

            $model=new M_model();
            $kui['kunci']='view_mini_bank';

            $id=session()->get('id');
            $where=array('id_user'=>$id);
            $kui['foto']=$model->getRow('user',$where);

            echo view('header',$kui);
            echo view('menu');
            echo view('laporan/filter');
            echo view('footer');

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function print_mini_bank()
    {
        $level = session()->get('level');

        if ($level == 1 || $level == 3) {
            $model = new M_model();
            $awal = $this->request->getPost('awal');
            $akhir = $this->request->getPost('akhir');
            $kui['duar'] = $model->filterTransaksi('transaksi_penyetoran', 'transaksi_penarikan', $awal, $akhir);

            $data=array(
                'id_user_log'=>session()->get('id'),
                'activity'=>"Menampilkan Laporan Transaksi Mini Bank Dengan Format Print",
                'tanggal_activity'=>date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity',$data);

            echo view('laporan/c_bank', $kui);

        } elseif ($level == 4) {
            $model = new M_model();
            $awal = $this->request->getPost('awal');
            $akhir = $this->request->getPost('akhir');
            $nama_nasabah = session()->get('nama_nasabah');
            $kui['duar'] = $model->filterTransaksiByNasabah('transaksi_penyetoran', 'transaksi_penarikan', $awal, $akhir, $nama_nasabah);

            $data=array(
                'id_user_log'=>session()->get('id'),
                'activity'=>"Menampilkan Laporan Transaksi Mini Bank Dengan Format Print",
                'tanggal_activity'=>date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity',$data);

            echo view('laporan/c_bank', $kui);

        } else {
            return redirect()->to('/home/dashboard');
        }
    }


    public function pdf_mini_bank()
    {
        if(session()->get('level')== 1 || session()->get('level')== 3 || session()->get('level')== 4) {

            $model=new M_model();
            $awal= $this->request->getPost('awal');
            $akhir= $this->request->getPost('akhir');
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filterTransaksiByNasabah('transaksi_penyetoran', 'transaksi_penarikan', $awal, $akhir, $nama_nasabah);
            } else {
            $kui['duar']=$model->filterTransaksi('transaksi_penyetoran', 'transaksi_penarikan',$awal,$akhir);
            }

            $data=array(
                'id_user_log'=>session()->get('id'),
                'activity'=>"Menampilkan Laporan Transaksi Mini Bank Dengan Format PDF",
                'tanggal_activity'=>date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity',$data);

            $dompdf = new\Dompdf\Dompdf();
            $dompdf->loadHtml(view('laporan/c_bank',$kui));
            $dompdf->setPaper('A4','landscape');
            $dompdf->render();
            $dompdf->stream('my.pdf', array('Attachment'=>0));

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function excel_mini_bank()
    {
        if (session()->get('level') == 1 || session()->get('level') == 3 || session()->get('level') == 4) {

            $model = new M_model();
            $awal = $this->request->getPost('awal');
            $akhir = $this->request->getPost('akhir');
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
                $duar = $model->filterTransaksiByNasabah('transaksi_penyetoran', 'transaksi_penarikan', $awal, $akhir, $nama_nasabah);
            } else {
                $duar = $model->filterTransaksi('transaksi_penyetoran', 'transaksi_penarikan', $awal, $akhir);
            }

            if (isset($duar['table1'], $duar['table2']) && is_iterable($duar['table1']) && is_iterable($duar['table2'])) {
                $penyetoran = $duar['table1'];
                $penarikan = $duar['table2'];

                $spreadsheet = new Spreadsheet();
                $spreadsheet->setActiveSheetIndex(0);

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A1', 'Tanggal')
                    ->setCellValue('B1', 'Keterangan')
                    ->setCellValue('C1', 'Transaksi')
                    ->setCellValue('D1', 'Debit')
                    ->setCellValue('E1', 'Kredit')
                    ->setCellValue('F1', 'Saldo');

                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFC0C0');

                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $no = 1;
                $totalDebit = 0;
                $totalKredit = 0;
                $totalSemua = 0;
                $runningBalance = 0;

                foreach ($penyetoran as $entry) {
                    $totalDebit += $entry->jumlah_penyetoran + $entry->jumlah_penarikan; // Updated this line
                    $totalKredit += $entry->jumlah_penyetoran + $entry->jumlah_penarikan; // Updated this line

                    $totalSemua += $entry->jumlah_penyetoran;
                    $runningBalance = $totalSemua;

                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . ($no + 1), $entry->tanggal_penyetoran)
                        ->setCellValue('B' . ($no + 1), 'Penyetoran')
                        ->setCellValue('C' . ($no + 1), 'Kas')
                        ->setCellValue('D' . ($no + 1), 'Rp. ' . number_format($entry->jumlah_penyetoran, 0, ',', '.'))
                        ->setCellValue('E' . ($no + 1), '~')
                        ->setCellValue('F' . ($no + 1), '');

                    $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 1) . ':F' . ($no + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('D' . ($no + 1))->getFont()->getColor()->setARGB('FF008000');

                    $spreadsheet->getActiveSheet()
                        ->setCellValue('A' . ($no + 2), '')
                        ->setCellValue('B' . ($no + 2), '')
                        ->setCellValue('C' . ($no + 2), 'Pemasukan')
                        ->setCellValue('D' . ($no + 2), '~')
                        ->setCellValue('E' . ($no + 2), 'Rp. ' . number_format($entry->jumlah_penyetoran, 0, ',', '.'))
                        ->setCellValue('F' . ($no + 2), 'Rp. ' . number_format($runningBalance, 0, ',', '.'));

                    $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 2) . ':F' . ($no + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('E' . ($no + 2))->getFont()->getColor()->setARGB('FFFF0000');

                    $no += 2;
                }

                foreach ($penarikan as $entry) {
                    if ($entry->status == "Penarikan Berhasil") {
                        $totalDebit += $entry->jumlah_penyetoran + $entry->jumlah_penarikan; // Updated this line
                        $totalKredit += $entry->jumlah_penyetoran + $entry->jumlah_penarikan; // Updated this line

                        $totalSemua -= $entry->jumlah_penarikan;
                        $runningBalance = $totalSemua;

                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . ($no + 1), $entry->tanggal_penarikan)
                            ->setCellValue('B' . ($no + 1), 'Penarikan')
                            ->setCellValue('C' . ($no + 1), 'Penarikan')
                            ->setCellValue('D' . ($no + 1), 'Rp. ' . number_format($entry->jumlah_penarikan, 0, ',', '.'))
                            ->setCellValue('E' . ($no + 1), '~')
                            ->setCellValue('F' . ($no + 1), '');

                        $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 1) . ':F' . ($no + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $spreadsheet->getActiveSheet()->getStyle('D' . ($no + 1))->getFont()->getColor()->setARGB('FF008000');

                        $spreadsheet->getActiveSheet()
                            ->setCellValue('A' . ($no + 2), '')
                            ->setCellValue('B' . ($no + 2), '')
                            ->setCellValue('C' . ($no + 2), 'Kas')
                            ->setCellValue('D' . ($no + 2), '~')
                            ->setCellValue('E' . ($no + 2), 'Rp. ' . number_format($entry->jumlah_penarikan, 0, ',', '.'))
                            ->setCellValue('F' . ($no + 2), 'Rp. ' . number_format($runningBalance, 0, ',', '.'));

                        $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 2) . ':F' . ($no + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $spreadsheet->getActiveSheet()->getStyle('E' . ($no + 2))->getFont()->getColor()->setARGB('FFFF0000');

                        $no += 2;
                    }
                }

                $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . ($no + 1), 'Jumlah')
                    ->setCellValue('B' . ($no + 1), '')
                    ->setCellValue('C' . ($no + 1), '')
                    ->setCellValue('D' . ($no + 1), 'Rp. ' . number_format($totalDebit, 0, ',', '.')) // Updated this line
                    ->setCellValue('E' . ($no + 1), 'Rp. ' . number_format($totalKredit, 0, ',', '.')) // Updated this line
                    ->setCellValue('F' . ($no + 1), 'Rp. ' . number_format($runningBalance, 0, ',', '.'));

                $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 1))->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('B' . ($no + 1))->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('C' . ($no + 1))->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('D' . ($no + 1))->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('E' . ($no + 1))->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('F' . ($no + 1))->getFont()->setBold(true);

                $spreadsheet->getActiveSheet()->getStyle('A' . ($no + 1) . ':F' . ($no + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $spreadsheet->getActiveSheet()->setCellValue('D' . ($no + 1), 'Rp. ' . number_format($totalDebit, 0, ',', '.'))->getStyle('D' . ($no + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('92d050');

                $spreadsheet->getActiveSheet()->setCellValue('E' . ($no + 1), 'Rp. ' . number_format($totalKredit, 0, ',', '.'))->getStyle('E' . ($no + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');

                $spreadsheet->getActiveSheet()->setCellValue('F' . ($no + 1), 'Rp. ' . number_format($runningBalance, 0, ',', '.'))->getStyle('F' . ($no + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

                $spreadsheet->getActiveSheet()->getStyle('A1:F' . ($no + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $fileName = 'Laporan Transaksi Mini Bank ~ ' . session()->get('username');

                $data = array(
                    'id_user_log' => session()->get('id'),
                    'activity' => "Menampilkan Laporan Transaksi Mini Bank Dengan Format Excel",
                    'tanggal_activity' => date('Y-m-d H:i:s')
                );
                $model->simpan('log_activity', $data);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            } else {
                return redirect()->to('/home/laporan_mini_bank')->with('error', 'Invalid data structure');
            }
        } else {
            return redirect()->to('/home/dashboard');
        }
    }


    public function print_penyetoran()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 4) {

            $model=new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filter_penyetoranBynasabah('transaksi_penyetoran', $nama_nasabah);
            } else {
            $kui['duar']=$model->filter_penyetoran('transaksi_penyetoran');
            }

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penyetoran Dengan Format Print",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            echo view('laporan/c_penyetoran',$kui);

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function pdf_penyetoran()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 4) {

            $model=new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filter_penyetoranBynasabah('transaksi_penyetoran', $nama_nasabah);
            } else {
            $kui['duar']=$model->filter_penyetoran('transaksi_penyetoran');
            }

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penyetoran Dengan Format PDF",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            $dompdf = new\Dompdf\Dompdf();
            $dompdf->loadHtml(view('laporan/c_penyetoran',$kui));
            $dompdf->setPaper('A4','landscape');
            $dompdf->render();
            $dompdf->stream('my.pdf', array('Attachment'=>0));

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function excel_penyetoran()
    {
        if (session()->get('level') == 1 || session()->get('level') == 2 || session()->get('level') == 4) {

            $model = new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
                $kui['duar'] = $model->filter_penyetoranBynasabah('transaksi_penyetoran', $nama_nasabah);
            } else {
                $kui['duar'] = $model->filter_penyetoran('transaksi_penyetoran');
            }

            $spreadsheet = new Spreadsheet();

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No Penyetoran')
                ->setCellValue('B1', 'NIK')
                ->setCellValue('C1', 'Nama Nasabah')
                ->setCellValue('D1', 'Tanggal')
                ->setCellValue('E1', 'Jenis Penyetoran')
                ->setCellValue('F1', 'Jumlah');

            $column = 2;

            foreach ($kui['duar'] as $data) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, 'MB-' . $data->id_penyetoran . ' (' . $data->id_nasabah_penyetoran . $data->maker_penyetoran . ')')
                    ->setCellValue('B' . $column, $data->nik)
                    ->setCellValue('C' . $column, $data->nama_nasabah)
                    ->setCellValue('D' . $column, $data->tanggal_penyetoran)
                    ->setCellValue('E' . $column, $data->jenis_penyetoran)
                    ->setCellValue('F' . $column, 'Rp. ' . number_format($data->jumlah_penyetoran, 0, ',', '.'));

                $column++;
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Laporan Penyetoran ~ ' . session()->get('username');

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penyetoran Dengan Format Excel",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } else {
            return redirect()->to('/home/dashboard');
        }
    }

    public function print_penarikan()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 4) {

            $model=new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filter_penarikanBynasabah('transaksi_penarikan', $nama_nasabah);
            } else {
            $kui['duar']=$model->filter_penarikan('transaksi_penarikan');
            }

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penarikan Dengan Format Print",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            echo view('laporan/c_penarikan',$kui);

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function pdf_penarikan()
    {
        if(session()->get('level')== 1 || session()->get('level')== 2 || session()->get('level')== 4) {

            $model=new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filter_penarikanBynasabah('transaksi_penarikan', $nama_nasabah);
            } else {
            $kui['duar']=$model->filter_penarikan('transaksi_penarikan');
            }

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penarikan Dengan Format PDF",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            $dompdf = new\Dompdf\Dompdf();
            $dompdf->loadHtml(view('laporan/c_penarikan',$kui));
            $dompdf->setPaper('A4','landscape');
            $dompdf->render();
            $dompdf->stream('my.pdf', array('Attachment'=>0));

        }else{
            return redirect()->to('/home/dashboard');
        }
    }

    public function excel_penarikan()
    {
        if (session()->get('level') == 1 || session()->get('level') == 2 || session()->get('level') == 4) {

            $model = new M_model();
            $nama_nasabah = session()->get('nama_nasabah');

            if (session()->get('level') == 4) {
            $kui['duar'] = $model->filter_penarikanBynasabah('transaksi_penarikan', $nama_nasabah);
            } else {
            $kui['duar']=$model->filter_penarikan('transaksi_penarikan');
            }

            $spreadsheet = new Spreadsheet();

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No Penyetoran')
                ->setCellValue('B1', 'NIK')
                ->setCellValue('C1', 'Nama Nasabah')
                ->setCellValue('D1', 'Tanggal')
                ->setCellValue('E1', 'Jenis Penyetoran')
                ->setCellValue('F1', 'Jumlah')
                ->setCellValue('G1', 'Keterangan');

            $column = 2;

            foreach ($kui['duar'] as $data) {
                if ($data->status == 'Penarikan Berhasil') {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $column, 'PPD-' . $data->id_penarikan . ' (' . $data->id_nasabah_penarikan . $data->maker_penarikan . ')')
                    ->setCellValue('B' . $column, $data->nik)
                    ->setCellValue('C' . $column, $data->nama_nasabah)
                    ->setCellValue('D' . $column, $data->tanggal_penarikan)
                    ->setCellValue('E' . $column, $data->jenis_penarikan)
                    ->setCellValue('F' . $column, 'Rp. ' . number_format($data->jumlah_penarikan, 0, ',', '.'))
                    ->setCellValue('G' . $column, $data->keterangan_penarikan);

                $column++;
            }}

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Laporan Penarikan ~ ' . session()->get('username');

            $data = array(
                'id_user_log' => session()->get('id'),
                'activity' => "Menampilkan Laporan Penarikan Dengan Format Excel",
                'tanggal_activity' => date('Y-m-d H:i:s')
            );
            $model->simpan('log_activity', $data);

            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } else {
            return redirect()->to('/home/dashboard');
        }
    }
}
