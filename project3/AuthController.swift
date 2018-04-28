//
//  AuthController.swift
//  project3
//
//  Created by Aron Gates on 3/22/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

//
//  ViewController.swift
//  project3
//
//  Created by Aron Gates on 1/24/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON

class AuthController: UIViewController  {
    
    var bigArray : [[String]] = []
    
    func getPOIS(email: String) -> Void {
        let urlString = "http://capstone3.herokuapp.com/new/get_user_pois.php"
        
        let parameters: [String: AnyObject] = [
            "user_id" : email as AnyObject,
            "latitude" : "password" as AnyObject,
            "longitude" : "username" as AnyObject
        ]
        
        Alamofire.request(urlString, method: .post, parameters: parameters, encoding: URLEncoding.httpBody).responseJSON {
            response in
            
            switch response.result {
            case .success(let value):
                let json = JSON(value)
                
                var bigArray : [[String]] = []
                
                for (_,subJson):(String, JSON) in json {
                    
                    var smallArray : [String] = []
                    
                    for (_,subJson2):(String, JSON) in subJson {
                        smallArray.append(subJson2.stringValue)
                    }
                    
                    bigArray.append(smallArray)
                }
                self.bigArray = bigArray
                self.performSegue(withIdentifier: "tolist", sender: nil)
                
                break
            case .failure(let error):
                print(error)
                break
            }
        }
    }
    
    var loginVisible = true
    
    @IBOutlet weak var loginEmail: UITextField!
    @IBOutlet weak var loginPassword: UITextField!
    @IBOutlet weak var loginView: UIView!
    
    @IBAction func login(_ sender: UIButton) {
        let urlString = "http://capstone3.herokuapp.com/new/login.php"
        
        if (loginEmail.text != nil && loginPassword.text != nil) {
            let email = loginEmail.text!
            let password = loginPassword.text!
            print(email,password)
            
            let parameters: [String: AnyObject] = [
                "email" : email as AnyObject,
                "password" : password as AnyObject,
            ]
            
            // test username: agates10@kent.edu
            // test password: helllo
            
            Alamofire.request(urlString, method: .post, parameters: parameters, encoding: URLEncoding.httpBody).responseJSON {
                response in
                
                switch response.result {
                case .success:
                    self.getPOIS(email: email)
                    break
                case .failure(let error):
                    print(error)
                    break
                }
            }
        }
    }
    
    @IBOutlet weak var registerUsername: UITextField!
    @IBOutlet weak var registerEmail: UITextField!
    @IBOutlet weak var registerPassword: UITextField!
    @IBOutlet weak var registerView: UIView!
    
    @IBAction func register(_ sender: UIButton) {
        let urlString = "http://capstone3.herokuapp.com/new/register.php"
        
        if (registerEmail.text != nil && registerPassword.text != nil && registerUsername.text != nil) {
            let email = registerEmail.text!
            let password = registerPassword.text!
            let username = registerUsername.text!
            print(email, password, username)
            
            let parameters: [String: AnyObject] = [
                "email" : email as AnyObject,
                "password" : password as AnyObject,
                "username" : username as AnyObject
            ]
            
            Alamofire.request(urlString, method: .post, parameters: parameters, encoding: URLEncoding.httpBody).responseJSON {
                response in
                
                print(response.description)
                
                switch response.result {
                case .success:
                    self.getPOIS(email: email)
                    break
                case .failure(let error):
                    print(error)
                    break
                }
            }
        }
    }
    
    @IBOutlet weak var switchButton: UIButton!
    @IBAction func switchViews(_ sender: UIButton) {
        if (loginVisible) {
            view.bringSubview(toFront: registerView)
            switchButton.setTitle("Have an account? Click here", for: .normal)
        } else {
            view.bringSubview(toFront: loginView)
            switchButton.setTitle("Need an account? Click here", for: .normal)
        }
        loginVisible = !loginVisible
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        let backItem = UIBarButtonItem()
        backItem.title = "Log Out"
        navigationItem.backBarButtonItem = backItem
        
        let destination = segue.destination as! POIViewController
        destination.fruits = self.bigArray
        
    }
    
}

