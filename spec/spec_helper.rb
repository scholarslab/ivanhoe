require 'dotenv'
require 'fileutils'
require 'ffaker'

require 'logger'
require 'mustache'
require 'mysql2'
require 'sequel'
require 'ruby-wpdb'

require 'capybara/rspec'
require 'capybara-webkit'
# require 'capybara-screenshot'
# require 'capybara-screenshot/rspec'

require 'helpers/media_files'

# Requires supporting files with macros, etc. Rails style;

Dir['./spec/support/**/*.rb'].each { |f| require f }

Dotenv.load

# Wordpress Tables
TABLE_NAMES = %w{
    wp_commentmeta
    wp_comments
    wp_links
    wp_options
    wp_postmeta
    wp_posts
    wp_terms
    wp_term_relationships
    wp_term_taxonomy
    wp_usermeta
    wp_users
  }

CHANGING_TABLES = %w{
    wp_postmeta
    wp_posts
  }

# You need to set up this database in mysql.
WP_DIR         = ENV.fetch('WP_DIR', '../../..')
WP_CONFIG      = ENV.fetch('WP_CONFIG', "#{WP_DIR}/wp-config.php")

DB_HOST        = ENV.fetch('DB_HOST', 'localhost')
WP_DB_HOST     = ENV.fetch('WP_DB_HOST', DB_HOST)
DB_USER        = ENV.fetch('DB_USER', 'ivanhoe')
WP_DB_USER     = ENV.fetch('WP_DB_USER', DB_USER)
DB_PASSWORD    = ENV.fetch('DB_PASSWORD', 'ivanhoe')
WP_DB_PASSWORD = ENV.fetch('WP_DB_PASSWORD', DB_PASSWORD)
DB_NAME        = ENV.fetch('DB_NAME', 'test_ivanhoe')
DB_PORT        = ENV.fetch('DB_PORT', '3306')
WP_DB_PORT     = ENV.fetch('WP_DB_PORT', DB_PORT)
URL_BASE       = ENV.fetch('URL_BASE', 'http://localhost:8888/ivanhoe')

DB_DUMP        = "./spec/dumps/ivanhoe.sql"

Capybara.default_driver    = :webkit
Capybara.javascript_driver = :webkit

def db_setup
  cxn = Mysql2::Client.new(
    :host => DB_HOST,
    :username => DB_USER,
    :password => DB_PASSWORD,
    :port => DB_PORT
  )
  puts "Creating database..."

  cxn.query("CREATE DATABASE IF NOT EXISTS #{DB_NAME};")

  puts "importing data"

  mustache = IO.read DB_DUMP
  stdin, _, _ = Open3.popen3(
    "mysql -h #{DB_HOST} --port #{DB_PORT} " \
      "-u #{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME}")
  sql = Mustache.render(mustache, :URL_BASE => URL_BASE)
  sql.each_line do |line|
    stdin.puts line
  end

  cxn.query("USE #{DB_NAME};")

  TABLE_NAMES.each do |name|
    cxn.query("DELETE FROM `#{name}`;")
    cxn.query("INSERT INTO `#{name}` SELECT * FROM `copy_#{name}`;")
    sleep(0.1)
  end

  cxn.close

end

RSpec.configure do |config|

  config.before(:all) do |ex|

    db_setup
    @cxn = Sequel.connect(
      :adapter  => 'mysql2',
      :database => DB_NAME,
      :host     => DB_HOST,
      :user     => DB_USER,
      :password => DB_PASSWORD,
      :port     => DB_PORT,
      # TODO: Remove the next line:
      # :loggers  => [Logger.new($stdout)]
    )
    @wpdb = WPDB.init("mysql2://#{DB_USER}:#{DB_PASSWORD}@#{DB_HOST}:#{DB_PORT}/#{DB_NAME}")

  end

  config.after(:all) do

    @cxn.disconnect unless @cxn.nil?

  end

  config.before(:each) do

    CHANGING_TABLES.each do |name|
      table = @cxn[name.to_sym]
      table.delete
      table.insert(@cxn["copy_#{name}".to_sym])
    end

    page.driver.allow_url("0.gravatar.com")
    page.driver.allow_url("1.gravatar.com")
    page.driver.allow_url("ajax.googleapis.com")
    page.driver.allow_url("fonts.googleapis.com")

  end

  # Patch wp config for testing.
  Dir.mkdir('./tmp') unless Dir.exists?('./tmp')

  FileUtils.cp(WP_CONFIG, './tmp/wp-config.php')
  File.open(WP_CONFIG, mode='w') do |f|
    IO.readlines('./tmp/wp-config.php').each do |line|
      if line.start_with?("define('DB_NAME'")
        line = "define('DB_NAME',     '#{DB_NAME}');\n# #{line}"
      elsif line.start_with?("define('DB_USER'")
        line = "define('DB_USER',     '#{WP_DB_USER}');\n# #{line}"
      elsif line.start_with?("define('DB_PASSWORD'")
        line = "define('DB_PASSWORD', '#{WP_DB_PASSWORD}');\n# #{line}"
      elsif line.start_with?("define('DB_HOST'")
        line = "define('DB_HOST',     '#{WP_DB_HOST}:#{WP_DB_PORT}');\n# #{line}"
      elsif line.start_with?("$table_prefix")
        line = "$table_prefix = 'wp_';\n# #{line}"
      end

      f.write(line)
    end
  end

  config.after(:suite) do |ex|

    FileUtils.cp('./tmp/wp-config.php', WP_CONFIG)

  end

  config.after(:suite) do |ex|
    #cxn = Mysql2::Client.new(
      #:host => DB_HOST,
      #:username => DB_USER,
      #:password => DB_PASSWORD
    #)

    #cxn.query("DROP DATABASE IF EXISTS #{DB_NAME};")
    #cxn.close
  end

end

