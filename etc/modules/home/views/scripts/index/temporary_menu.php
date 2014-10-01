<div class="menu">
			<ul>
			<li><a class="current" href="<?php echo $this->basePath; ?>/home/index/home">Beranda</a></li>
			<li><a href="login.html">Manajemen Menu</a>
				<ul>
				<li><a class="sub1" href="<?php echo $this->basePath; ?>/home/index/menu" title="">Komponen</a>
					 <ul>
						<?php foreach($this->menuKomponen as $komponen) : ?>
						<li><a href="<?php echo $this->basePath; ?>/home/index/komponenmenu"><?php echo $this->escape($komponen->Komponen);?></a></li>
						<?php endforeach; ?>
						
					</ul>
				</li>
				<li><a class="sub1" href="<?php echo $this->basePath; ?>/home/index/menu" title="">Komponen Sub</a>
					 <ul>
						<?php foreach($this->menuKomponenSub as $komponen) : ?>
						<li><a href=""><?php echo $this->escape($komponen->SubKomponen);?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo $this->basePath; ?>/home/index/komponensubmenu">Pilihan Menu ..</a></li>
					</ul>
				</li>
				<li><a class="sub1" href="<?php echo $this->basePath; ?>/home/index/menu" title="">Komponen Sub Detail</a>
					 <ul>
						<?php foreach($this->menuKomponenSubDetail as $komponen) : ?>
						<li><a href=""><?php echo $this->escape($komponen->SubKomponenDetail);?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo $this->basePath; ?>/home/index/komponensubdetailmenu">Pilihan Menu ..</a></li>
					</ul>
				</li>
				<li><a class="sub1" href="" title="">Kecamatan</a>
					 <ul>
						<?php foreach($this->kecamatan as $kec) : ?>
						<li><a href=""><?php echo $this->escape($kec->nama_kecamatan);?></a></li>
						<?php endforeach; ?>
						<li><a href="">Pilihan Menu ..</a></li>
					</ul>
				</li>
				<li><a class="sub1" href="" title="">Kelurahan</a>
					 <ul>
						<?php foreach($this->kelurahan as $kel) : ?>
						<li><a href=""><?php echo $this->escape($kel->nama_kelurahan);?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo $this->basePath; ?>/home/index/kelurahanmenu">Pilihan Menu ..</a></li>
					</ul>
				</li>
			   </ul>
			<li><a href="login.html">Intervensi</a>
				<ul>
				<li><a class="sub1" href="" title="">Ekonomi</a>
					 <ul>
						<li><a href="<?php echo $this->basePath; ?>/home/index/retail">Retail Trading</a></li>
						<li><a href="" title="">Home Industry</a></li>
						<li><a href="" title="">Jasa</a></li>
						<li><a href="" title="">Human Resources Development</a></li>
						<li><a href="" title="">Lain-lain</a></li>
					</ul>
				</li>
				<li><a class="sub1" href="" title="">Sosial</a>
					<ul>
						<li><a href="" title="">Santunan Sosial / Hibah</a></li>
						<li><a href="" title="">Peningkatan SDM</a></li>
						<li><a href="" title="">Scholarship</a></li>
						<li><a href="" title="">Health</a></li>
						<li><a href="" title="">Other in Social</a></li>
					</ul>
				</li>
				<li><a class="sub1" href="" title="">Infrastruktur</a>
					<ul>
						<li><a href="" title="">Road</a></li>
						<li><a href="" title="">Drainage</a></li>
						<li><a href="" title="">Bridge</a></li>
						<li><a href="" title="">Housing</a></li>
						<li><a href="" title="">Public Toilet</a></li>
						<li><a href="" title="">Building School</a></li>
						<li><a href="" title="">Irigasi</a></li>
						<li><a href="" title="">Healthcare Facility</a></li>
						<li><a href="" title="">Waste Water Canal</a></li>
						<li><a href="" title="">Tambatan Perahu</a></li>
						<li><a href="" title="">Other in Infrastructure</a></li>
					</ul>
				</li> 
				<li><a href="<?php echo $this->basePath; ?>/home/index/datapenduduk">Lihat Data Penduduk</a>		
			   </ul>
			</li>
			<li><a href="">Contact</a></li>
			</ul>
		</div> 
		