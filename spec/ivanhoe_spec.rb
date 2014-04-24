
describe 'ivanhoe', :type => :feature, :js => true do
  it 'should be installed as a theme' do
    visit('http://localhost/')
    img = find('footer').find('img')
    img[:src].should have_content('ivanhoelogo.png')
  end
end

