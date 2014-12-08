require 'net/http'
require 'tempfile'
require 'uri'

def save_to_tempfile(url)
  uri = URI.parse(url)
  Net::HTTP.start(uri.host, uri.port) do |http|
    resp = http.get(uri.path)
    file = Tempfile.new('ivanhoe_file', Dir.tmpdir, 'wb+')
    file.write(resp.body)
    file
  end
end

def get_test_image(count = 1)
  count.times do
    width = rand(200..500)
    height = rand(200..500)
    save_to_tempfile("http://baconmockup.com/#{width}/#{height}")
  end
end
