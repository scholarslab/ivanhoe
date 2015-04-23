require 'dotenv'
require 'rspec/core/rake_task'

Dotenv.load

DB_HOST        = ENV.fetch('DB_HOST', 'localhost')
DB_USER        = ENV.fetch('DB_USER', 'ivanhoe')
DB_PASSWORD    = ENV.fetch('DB_PASSWORD', 'ivanhoe')
WP_DB_PASSWORD = ENV.fetch('WP_DB_PASSWORD', DB_PASSWORD)
DB_NAME        = ENV.fetch('DB_NAME', 'test_ivanhoe')
DB_PORT        = ENV.fetch('DB_PORT', '8889')
URL_BASE       = ENV.fetch('URL_BASE', 'http://localhost:8888/ivanhoe')

DB_DUMP        = "./spec/dumps/ivanhoe.sql"


task :default => :spec

RSpec::Core::RakeTask.new(:spec)

namespace :db do

    desc "create backup tables"
    task :copy_tables do

       system "mysql -h #{DB_HOST} --port #{DB_PORT} -u #{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME}  < spec/sql/duplicate_tables.sql"

    end

    desc "restore tables from backup"
    task :restore do

        system "cat #{DB_DUMP} | sed 's,URL_BASE,#{URL_BASE},g' | mysql -h #{DB_HOST} --port #{DB_PORT} -u #{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME} 2> /dev/null"

    end

    desc "dump database"
    task :dump do

           system "mysqldump -h #{DB_HOST} --port #{DB_PORT} -u #{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME}  > #{DB_DUMP}"

    end

end
