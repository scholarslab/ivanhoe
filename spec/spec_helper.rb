
require 'fileutils'

require 'capybara/rspec'
require 'capybara-webkit'
# require 'capybara-screenshot/rspec'

require 'pg'

WP_CONFIG   = '../../../wp-config.php'

# You need to set up this database in postgres.

DB_HOST     = ENV.fetch('DB_HOST',     'localhost')
DB_USER     = ENV.fetch('DB_USER',     'ivanhoe')
DB_PASSWORD = ENV.fetch('DB_PASSWORD', 'ivanhoe')
DB_NAME     = ENV.fetch('DB_NAME',     'test_ivanhoe')

DB_DUMP     = './spec/dumps/ivanhoe.sql'

Capybara.default_driver    = :webkit
Capybara.javascript_driver = :webkit

RSpec.configure do |config|

  config.before(:suite) do |ex|
    Dir.mkdir('./tmp') unless Dir.exists?('./tmp')

    FileUtils.cp(WP_CONFIG, './tmp/wp-config.php')
    File.open(WP_CONFIG, mode='w') do |f|
      IO.readlines('./tmp/wp-config.php').each do |line|
        if line.start_with?("define('DB_NAME'")
          line = "define('DB_NAME',     '#{DB_NAME}');\n# #{line}"
        elsif line.start_with?("define('DB_USER'")
          line = "define('DB_USER',     '#{DB_USER}');\n# #{line}"
        elsif line.start_with?("define('DB_PASSWORD'")
          line = "define('DB_PASSWORD', '#{DB_PASSWORD}');\n# #{line}"
        elsif line.start_with?("define('DB_HOST'")
          line = "define('DB_HOST',     '#{DB_HOST}');\n# #{line}"
        end

        f.write(line)
      end
    end
  end

  config.after(:suite) do |ex|
    FileUtils.cp('./tmp/wp-config.php', WP_CONFIG)
  end

  config.before(:suite) do |ex|
    cxn = PG.connect(:host => DB_HOST,
                     :user => DB_USER, :password => DB_PASSWORD)
    cxn.exec("CREATE DATABASE #{DB_NAME};")
    cxn.close

    system("psql -h#{DB_HOST} -U#{DB_USER} -d#{DB_NAME} -w < #{DB_DUMP}")
  end

  config.after(:suite) do |ex|
    cxn = PG.connect(:host => DB_HOST,
                     :user => DB_USER, :password => DB_PASSWORD)
    cxn.exec("DROP DATABASE #{DB_NAME};")
    cxn.close
  end

end

