<?php
namespace Codeception\Module;


use Codeception\Lib\ModuleContainer;

class WPDbTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var ModuleContainer
     */
    protected $moduleContainer;

    /**
     * @var array
     */
    protected $config;

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->make_instance();

        $this->assertInstanceOf(WPDb::class, $sut);
    }

    /**
     * @return WPDb
     */
    private function make_instance()
    {
        return new WPDb($this->moduleContainer->reveal(), $this->config);
    }

    /**
     * @test
     * it should not replace the site domain if home is not set in dump
     */
    public function it_should_not_replace_the_site_domain_if_home_is_not_set_in_dump()
    {
        $sut = $this->make_instance();

        $sql = <<< SQL
LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
-- noinspection SqlNoDataSourceInspection
INSERT INTO `wp_options` VALUES (1,'siteurl','http://original.dev/wp','yes'),(3,'blogname','Tribe Premium Plugins','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@original.dev','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),'alabar_last_save_post','1465471896','yes');SQL;
SQL;

        $sql = $sut->replaceSiteDomainInSql($sql);

        $this->assertRegExp('~.*original.dev/wp.*~', $sql);
        $this->assertNotRegExp('/.*some-wp.dev.*/', $sql);
    }

    /**
     * @test
     * it should not replace the site domain if site domain is same
     */
    public function it_should_not_replace_the_site_domain_if_site_domain_is_same()
    {
        $this->config['url'] = 'http://original.dev';
        $sut = $this->make_instance();

        $sql = <<< SQL
LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
-- noinspection SqlNoDataSourceInspection
INSERT INTO `wp_options` VALUES (1,'siteurl','http://original.dev/wp','yes'),(2,'home','http://original.dev/wp','yes'),(3,'blogname','Tribe Premium Plugins','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@original.dev','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),'alabar_last_save_post','1465471896','yes');
SQL;

        $sql = $sut->replaceSiteDomainInSql($sql);

        $this->assertRegExp('~.*original.dev.*~', $sql);
        $this->assertNotRegExp('/.*some-wp.dev.*/', $sql);
    }

    /**
     * @test
     * it should replace the site domain in dump
     */
    public function it_should_replace_the_site_domain_in_dump()
    {
        $sut = $this->make_instance();

        $sql = <<< SQL
LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
-- noinspection SqlNoDataSourceInspection
INSERT INTO `wp_options` VALUES (1,'siteurl','http://original.dev/wp','yes'),(2,'home','http://original.dev/wp','yes'),(3,'blogname','Tribe Premium Plugins','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@original.dev','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),'alabar_last_save_post','1465471896','yes');
SQL;

        $sql = $sut->replaceSiteDomainInSql($sql);

        $this->assertRegExp('/.*some-wp.dev.*/', $sql);
        $this->assertNotRegExp('~.*original.dev/wp.*~', $sql);
    }

    /**
     * @test
     * it should replace https scheam with http
     */
    public function it_should_replace_https_schema_with_http()
    {
        $sut = $this->make_instance();

        $sql = <<< SQL
LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
-- noinspection SqlNoDataSourceInspection
INSERT INTO `wp_options` VALUES (1,'siteurl','https://original.dev/wp','yes'),(2,'home','https://original.dev/wp','yes'),(3,'blogname','Tribe Premium Plugins','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@original.dev','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),'alabar_last_save_post','1465471896','yes');
SQL;

        $sql = $sut->replaceSiteDomainInSql($sql);

        $this->assertRegExp('~.*http:\\/\\/some-wp.dev.*~', $sql);
        $this->assertNotRegExp('~.*https:\\/\\/original.dev/wp.*~', $sql);
    }

    /**
     * @test
     * it should replace http schema with https
     */
    public function it_should_replace_http_schema_with_https()
    {
        $this->config['url'] = 'https://some-wp.dev';
        $sut = $this->make_instance();

        $sql = <<< SQL
LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
-- noinspection SqlNoDataSourceInspection
INSERT INTO `wp_options` VALUES (1,'siteurl','http://original.dev/wp','yes'),(2,'home','http://original.dev/wp','yes'),(3,'blogname','Tribe Premium Plugins','yes'),(4,'blogdescription','Just another WordPress site','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@original.dev','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','0','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),'alabar_last_save_post','1465471896','yes');
SQL;

        $sql = $sut->replaceSiteDomainInSql($sql);

        $this->assertRegExp('~.*https:\\/\\/some-wp.dev.*~', $sql);
        $this->assertNotRegExp('~.*https:\\/\\/original.dev/wp.*~', $sql);
    }

    protected function _before()
    {
        $this->moduleContainer = $this->prophesize(ModuleContainer::class);
        $this->config = [
            'dsn' => 'some-dsn',
            'user' => 'some-user',
            'password' => 'some-password',
            'url' => 'http://some-wp.dev',
            'tablePrefix' => 'wp_'
        ];
    }

    protected function _after()
    {
    }
}