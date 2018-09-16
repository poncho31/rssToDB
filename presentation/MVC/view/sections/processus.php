<section class="banner2 gilles">
	<div class="inner">
		<h1 class="gilles-homepage">
			<div>Le processus de l'application</div>
		</h1>
	</div>
</section>

<section>
	<div class="content">
	<h4><div>Comment est-ce que cela se déroule ?</div></h4><br><br>

<!-- 1 -->
<div class="process"><h3>A. Administration de la BDD</h3></div><br>

		<div>
			<h2>1. Traitements des flux RSS <span>avant</span> importation en BDD</h2>
			<ul>
				<h3></h3>
				<li>Récupération et stockage des liens RSS (<span>manuel</span>)</li>
				<li>Récupération des données des flux RSS (<span>automatisé</span>)</li>
				<li>Nettoyage des données</li>
				<h5><div>&#x2192; Importation en BDD</div></h5>
			</ul>
		</div>
		<br>
<hr>

		<div>
			<h2>2. Traitements des flux RSS <span>après</span> importation en BDD</h2>
			<ul>
				<h3></h3>
				<li>Lier les politiciens aux articles</li>
				<li>Vérification de concordance (<span>nom + prénom</span> <=> <span>description article</span>)</li>
				<li>Insertion des liaisons en BDD</li>
				<h5><div>&#x2192;  Sauvegarde de la BDD</div></h5>
			</ul>
		</div><br>
<hr>
		<div>
			<h2>3. Visuel de l'administration de la BDD</h2>
			<img class="imgAdminBDD" src="MVC/design/img/administrationBDD.png"  alt="">
		</div><br>
<hr>


<!-- 2 -->
<div class="process"><h3>B. Administration de l'application</h3></div><br>
		<div>
			<h2>1. Côté serveur (<span>Symfony</span>)</h2>
			<ul>
				<h3>Deux types de requêtes</h3>
				<li>les requêtes de longues durées
				</li>
				<img class="imgAdminBDD" src="MVC/design/img/lowQuerySymfony.png"  alt="">
				<img class="imgAdminBDD" src="MVC/design/img/lowQueryJSONreturnSymfony.png"  alt="">

				<li>les requêtes de courtes durées
				</li>
				<img class="imgAdminBDD" src="MVC/design/img/fastQuerySymfony.png"  alt="">
				<br><br>
				<h5>
					<div>&#x2192;  Symfony est utilisé en tant qu'API (renvoit du JSON)</div>
				</h5>
				<h5></h5>
				
			</ul>
		</div><br>
<hr>
		<div>
			<h2>2. Côté client (<span>Angular</span>)</h2>
			<ul>
				<h3>Deux types de requêtes</h3>
				<li>les requêtes automatiques</li>
				<img class="imgAdminBDD" src="MVC/design/img/administrationBDD.png"  alt="">

				<li>les requêtes en fonction de critères</li>
				<img class="imgAdminBDD" src="MVC/design/img/administrationBDD.png"  alt="">
				<br><br>
				<h5><div>&#x2192;  Angular communique avec symfony au moyen de requêtes AJAX</div></h5>
			</ul>
		</div><br>

</div>
</section>