require 'dotenv'
require 'fileutils'
require 'ffaker'

require 'mysql2'

require 'capybara/rspec'
require 'capybara-webkit'
require 'capybara-screenshot'
require 'capybara-screenshot/rspec'

require 'helpers/media_files'

# Requires supporting files with macros, etc. Rails style;

Dir['./spec/support/**/*.rb'].each { |f| require f }

Dotenv.load

# You need to set up this database in mysql.
WP_CONFIG      = ENV.fetch('WP_CONFIG', '../../../wp-config.php')

DB_HOST        = ENV.fetch('DB_HOST', 'localhost')
DB_USER        = ENV.fetch('DB_USER', 'ivanhoe')
DB_PASSWORD    = ENV.fetch('DB_PASSWORD', 'ivanhoe')
WP_DB_PASSWORD = ENV.fetch('WP_DB_PASSWORD', DB_PASSWORD)
DB_NAME        = ENV.fetch('DB_NAME', 'test_ivanhoe')
DB_PORT        = ENV.fetch('DB_PORT', '8889')
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
  reset_db

  cxn.close
end

def reset_db
   system "cat #{DB_DUMP} | sed 's,URL_BASE,#{URL_BASE},g' | mysql -h #{DB_HOST} --port #{DB_PORT} -u #{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME} 2> /dev/null"
end


RSpec.configure do |config|

  config.before(:suite) do |ex|

    db_setup

  end

  config.before(:each) do
    reset_db
  end

  # Patch wp config for testing.
  Dir.mkdir('./tmp') unless Dir.exists?('./tmp')

  FileUtils.cp(WP_CONFIG, './tmp/wp-config.php')
  File.open(WP_CONFIG, mode='w') do |f|
    IO.readlines('./tmp/wp-config.php').each do |line|
      if line.start_with?("define('DB_NAME'")
        line = "define('DB_NAME',     '#{DB_NAME}');\n# #{line}"
      elsif line.start_with?("define('DB_USER'")
        line = "define('DB_USER',     '#{DB_USER}');\n# #{line}"
      elsif line.start_with?("define('DB_PASSWORD'")
        line = "define('DB_PASSWORD', '#{WP_DB_PASSWORD}');\n# #{line}"
      elsif line.start_with?("define('DB_HOST'")
        line = "define('DB_HOST',     '#{DB_HOST}:#{DB_PORT}');\n# #{line}"
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

