<div class="main-container">
    <section class="col-xs-12 col-md-12 shadow max-size xs-mb-20">
        <div class="profilHeader-box">
            <div class="content">
                <div class="avatar-box"><svg class="user">
                        <use xlink:href="#user"></use>
                    </svg></div>
                <div class="details">
                    <h1 class="name">Jean Dupont</h1>
                    <h2 class="formation">Étudiant - L1 Langues étrangères</h2>
                </div>
            </div>
            <div class="background"></div>
        </div>
    </section>
    <section class="col-xs-12 col-md-9 shadow">
        <div class="bg-bgWhite max-size">
            <div class="tabs-container">
                <div class="tabs-menu">
                    <div data-tab="rapidposts" class="tab col-xs-4 active">
                        <svg class="rapidpost">
                            <use xlink:href="#rapidpost"></use>
                        </svg>
                        <span>Messages thématiques</span>
                    </div>
                    <div data-tab="blogposts" class="tab col-xs-4 ">
                        <svg class="blogpost">
                            <use xlink:href="#blogpost"></use>
                        </svg>
                        <span>Articles de blog</span>
                    </div>
                    <div data-tab="offers" class="tab col-xs-4 ">
                        <svg class="market">
                            <use xlink:href="#market"></use>
                        </svg>
                        <span>Place du marché</span>
                    </div>
                </div>
                <div class="tabs-content">
                    <div data-tab-content="rapidposts" class="tab-content active">
                        <?php include('rapidposts_list.php') ?>
                    </div>
                    <div data-tab-content="blogposts" class="tab-content">
                        <?php include('blogposts_list.php') ?>
                    </div>
                    <div data-tab-content="offers" class="tab-content">
                        <?php include('offers_list.php') ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <aside class="col-xs-12 col-md-3 shadow">
        <div class="bg-bgWhite max-size">
            <div class=" xs-py-10 xs-px-10">
                <a href="#" class="button orange"><svg class="enveloppe">
                        <use xlink:href="#enveloppe"></use>
                    </svg><span>Contacter Jean</span></a>
            </div>
        </div>

    </aside>
</div>